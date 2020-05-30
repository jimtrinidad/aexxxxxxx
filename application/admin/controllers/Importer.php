<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

class Importer extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        // require login
        // check_authentication();
    }

    public function index()
    {
        check_authentication();
        $viewData = array(
            'pageTitle'         => 'Data Importer',
            'pageDescription'   => '',
            'content_header'    => false,
            'accountInfo'       => user_account_details(),
            'jsModules'         => array(
                'importer',
                'utils'
            ),
        );

        $page_limit = 50;
        $page_start = (int) $this->uri->segment(3);

        $where = array();
        $order = 'id';

        $paginatationData = $this->mgovdb->getPaginationData('migration_groups', $page_limit, $page_start, $where, $order);

        // prepare data
        $records = array();
        foreach ($paginatationData['data'] as $item) {
            $location = lookup_address(lookup_get_location_breakdown($item->scope, $item->location));
            $item->location = implode(', ', array_filter(array_values($location)));
            $item->scope    = lookup('location_scope', $item->scope);
            $item->status   = lookup('data_importer_status', $item->status);
            $records[] = (array) $item;
        }

        $paginationConfig = array(
            'base_url'      => base_url('importer/index'),
            'total_rows'    => $paginatationData['count'],
            'per_page'      => $page_limit,
            'full_tag_open' => '<ul class="pagination pagination-sm no-margin pull-right">'
        );

        // echo '<pre>';print_r($records);exit;

        $viewData['groups']   = $records;
        $viewData['pagination'] = paginate($paginationConfig);

        view('pages/importer/index', $viewData, 'templates/mgovadmin');
    }

    public function upload_group()
    {
        check_authentication();
        if (validate('save_import_group') == FALSE) {
            $return_data = array(
                'status'    => false,
                'message'   => 'Some fields have errors.',
                'fields'    => validation_error_array()
            );
        } else {

            // if scope is regional or lower, require location
            if (get_post('scope') > 1) {
                if (empty(get_post('location'))) {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Some fields have errors.',
                        'fields'    => array('location' => 'Location is required.')
                    );
                    response_json($return_data);
                    return;
                }
            }

            $randomName = md5(microsecID());

            // validate file upload
            $this->load->library('upload', array(
                'upload_path'   => UPLOADS_DIRECTORY,
                'allowed_types' => 'xlsx|xls',
                'max_size'      => '2000', // 2mb
                'overwrite'     => false,
                'file_name'     => $randomName
            ));

            if ($this->upload->do_upload('file') == false) {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Uploading file failed.',
                    'fields'    => array('file' => $this->upload->display_errors('',''))
                );
            } else {

                // do save
                $uploadData     = $this->upload->data();

                
                $insertData     = array(
                    'code'          => microsecID(),
                    'name'          => get_post('name'),
                    'service'       => get_post('service'),
                    'file'          => $uploadData['file_name'],
                    'scope'         => get_post('scope'),
                    'location'      => get_post('location'),
                    'status'        => 1,
                    'count'         => 0,
                    'date_added'    => date('Y-m-d H:i:s'),
                    'last_update'   => date('Y-m-d H:i:s')
                );

                if (($ID = $this->mgovdb->saveData('migration_groups', $insertData))) {
                    $return_data = array(
                        'status'    => true,
                        'message'   => 'Migration group has been saved successfully. Record importation will run on server.',
                        'id'        => $ID,
                        'data'      => $insertData
                    );

                } else {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Saving document failed. Please try again.'
                    );
                    @unlink($uploadData['full_path']);
                }

            }
        }
        response_json($return_data);
    }

    public function view($code)
    {

        check_authentication();
        $record = $this->mgovdb->getRowObject('migration_groups', $code, 'code');
        if ($record) {

            $viewData = array(
                'pageTitle'         => 'Data Importer | ' . strtoupper($record->name),
                'pageDescription'   => strtoupper($record->name) . ' Records',
                'content_header'    => false,
                'accountInfo'       => user_account_details(),
                'jsModules'         => array(
                    'importer',
                    'utils'
                ),
            );

            $page_limit = 100;
            $page_start = (int) $this->uri->segment(4);

            $where = array(
                'group_id'  => $record->id
            );
            $order = 'fullname';

            $paginatationData = $this->mgovdb->getPaginationData('migration_items', $page_limit, $page_start, $where, $order);

            // prepare data
            $records = array();
            foreach ($paginatationData['data'] as $k => $item) {
                $item->ctr = $page_start + $k + 1;
                $records[] = (array) $item;
            }

            $paginationConfig = array(
                'base_url'      => base_url('importer/view/' . $code),
                'uri_segment'   => 4,
                'total_rows'    => $paginatationData['count'],
                'per_page'      => $page_limit,
                'full_tag_open' => '<ul class="pagination pagination-sm no-margin pull-right">'
            );

            // echo '<pre>';print_r($records);exit;

            $viewData['itemCount']  = $paginatationData['count'];
            $viewData['items']      = $records;
            $viewData['pagination'] = paginate($paginationConfig);

            view('pages/importer/view', $viewData, 'templates/mgovadmin');
        } else {
            redirect('importer');
        }
    }

    public function reader()
    {

        if (! $this->input->is_cli_request()) {
            show_404();
        }

        $to_process = $this->mgovdb->getRecords('migration_groups', array('status' => 1));
        $status     = 1;
        foreach ($to_process as $item) {
            $input_file = UPLOADS_DIRECTORY . $item['file'];

            if (file_exists($input_file)) {

                // set status to processing
                $this->mgovdb->saveData('migration_groups', array('id' => $item['id'], 'status' => 2, 'last_update' => datetime()));
                $processed_count = $item['count'];

                $input_type = ucfirst(pathinfo($input_file, PATHINFO_EXTENSION));

                /**  Create a new Reader of the type defined in $inputFileType  **/
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($input_type);
                
                // Define how many rows we want to read for each "chunk"
                $chunkSize = 200;
                // Create a new Instance of our Read Filter
                $chunkFilter = new ChunkReadFilter();

                // Tell the Reader that we want to use the Read Filter that we've Instantiated
                $reader->setReadFilter($chunkFilter);

                // Loop to read our worksheet in "chunk size" blocks
                for ($startRow = 1; $startRow <= 100000; $startRow += $chunkSize) {
                    syslog(LOG_INFO, 'Processing migration ['.$item['name'].'] from rows ' . $startRow . ' to ' . ($startRow + $chunkSize - 1));
                    // Tell the Read Filter, the limits on which rows we want to read this iteration
                    $chunkFilter->setRows($startRow, $chunkSize);
                    // Load only the rows that match our filter from $inputFileName to a PhpSpreadsheet Object
                    $spreadsheet = $reader->load($input_file);

                    // Do some processing here

                    $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
                    array_shift($sheetData);
                    foreach ($sheetData as $row) {
                        // name is not empty
                        if (isset($row['B']) && !empty($row['B'])) {
                            $saveData = array(
                                'group_id'  => $item['id'],
                                'fullname'  => $row['B'],
                                'testing_facility'      => $row['D'] ?? '',
                                'testing_date_taken'    => $row['E'] ?? '',
                                'testing_date_release'  => $row['F'] ?? '',
                                'region'                => $row['J'] ?? '',
                                'province'              => $row['I'] ?? '',
                                'city'                  => $row['K'] ?? '',
                                'status'                => 1,
                                'last_update'           => date('Y-m-d H:i:s')
                            );
                            if ($this->mgovdb->saveData('migration_items', $saveData)) {
                                $processed_count++;
                            }
                        }
                    }

                    // update count every chuck
                    $this->mgovdb->saveData('migration_groups', array('id' => $item['id'], 'count' => $processed_count, 'last_update' => datetime()));

                    if ((count($sheetData) + 1) < $chunkSize) {
                        break;
                    }
                }

                if ($processed_count) {
                    // completed with records
                    $this->mgovdb->saveData('migration_groups', array('id' => $item['id'], 'status' => 3, 'last_update' => datetime()));
                } else {
                    // no record found
                    $this->mgovdb->saveData('migration_groups', array('id' => $item['id'], 'status' => 4, 'last_update' => datetime()));
                }


            } else {
                // set status to 5 - missing file
                $this->mgovdb->saveData('migration_groups', array('id' => $item['id'], 'status' => 5, 'last_update' => datetime()));
            }
        }

    }

}


/**  Define a Read Filter class implementing IReadFilter  */
class ChunkReadFilter implements IReadFilter
{
    private $startRow = 0;

    private $endRow = 0;

    /**
     * Set the list of rows that we want to read.
     *
     * @param mixed $startRow
     * @param mixed $chunkSize
     */
    public function setRows($startRow, $chunkSize): void
    {
        $this->startRow = $startRow;
        $this->endRow = $startRow + $chunkSize;
    }

    public function readCell($column, $row, $worksheetName = '')
    {
        //  Only read the heading row, and the rows that are configured in $this->_startRow and $this->_endRow
        if (($row == 1) || ($row >= $this->startRow && $row < $this->endRow)) {
            return true;
        }

        return false;
    }
}