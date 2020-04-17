<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Documents extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        // require login
        check_authentication();

        if ($this->session->userdata('alevel') < 13) {
            show_404();
        }

    }

    /**
    * main and sub department listing and manager
    */
    public function index()
    {
        $viewData = array(
            'pageTitle'         => 'Digital Document Manager',
            'accountInfo'       => user_account_details(),
            'jsModules'         => array(
                'utils',
                'documents'
            ),
        );

        $page_limit = 50;
        $page_start = (int) $this->uri->segment(3);

        $where = array(
            'deletedAt' => NULL
        );
        $order = 'Name';

        $paginatationData = $this->mgovdb->getPaginationData('Doc_Templates', $page_limit, $page_start, $where, $order);

        // prepare data
        $records = array();
        foreach ($paginatationData['data'] as $item) {
            $records[] = prepare_document_data($item);
        }

        $paginationConfig = array(
            'base_url'      => base_url('documents/index'),
            'total_rows'    => $paginatationData['count'],
            'per_page'      => $page_limit,
            'full_tag_open' => '<ul class="pagination pagination-sm no-margin pull-right">'
        );

        // echo '<pre>';print_r($records);exit;

        $viewData['documents']   = $records;
        $viewData['pagination'] = paginate($paginationConfig);

        view('pages/documents/index', $viewData, 'templates/mgovadmin');
    }

    /*
    * save document base info
    */
    public function save_document()
    {
        if (validate('save_document') == FALSE) {
            $return_data = array(
                'status'    => false,
                'message'   => 'Some fields have errors.',
                'fields'    => validation_error_array()
            );
        } else {

            $randomLogoName = md5(microsecID());

            // validate file upload
            $this->load->library('upload', array(
                'upload_path'   => LOGO_DIRECTORY,
                'allowed_types' => 'gif|jpg|png',
                'max_size'      => '2000', // 2mb
                'max_width'     => '1024',
                'max_height'    => '768',
                'overwrite'     => true,
                'file_name'     => $randomLogoName
            ));

            if (!empty($_FILES['Logo']['name']) && $this->upload->do_upload('Logo') == false) {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Uploading logo failed.',
                    'fields'    => array('Logo' => $this->upload->display_errors('',''))
                );
            } else {

                // do save
                $uploadData     = $this->upload->data();

                $fields         = $this->mgovdb->tableColumns('Doc_Templates');
                $excludeField   = array('id', 'Logo', 'deletedAt', 'Code', 'DepartmentID', 'SubDepartmentID');

                $existingData   = false;
                $insertData     = array(
                    'LastUpdate'  => date('Y-m-d H:i:s')
                );

                // sub department
                if (stripos(get_post('DepartmentID'), '-') !== false) {
                    $tmp = explode('-', get_post('DepartmentID'));
                    $insertData['DepartmentID']     = $tmp[0];
                    $insertData['SubDepartmentID']  = $tmp[1];
                } else {
                    $insertData['DepartmentID']     = get_post('DepartmentID');
                }

                foreach ($fields as $field) {
                    if (!in_array($field, $excludeField) && !array_key_exists($field, $insertData) && get_post($field) !== null) {
                        $insertData[$field] = get_post($field);
                    }
                }

                if (!empty($_FILES['Logo']['name'])) {
                    $insertData['Logo'] = $uploadData['file_name'];
                }

                if (get_post('id')) {
                    $existingData = $this->mgovdb->getRowObject('Doc_Templates', get_post('id'), 'id');
                }

                if ($existingData) {
                    $insertData['id']        = $existingData->id;
                    $insertData['UpdatedBy'] = current_user();
                } else {
                    $insertData['Code']      = microsecID();
                    $insertData['CreatorID'] = current_user();
                    $insertData['DateAdded'] = date('Y-m-d H:i:s');
                }

                if (($ID = $this->mgovdb->saveData('Doc_Templates', $insertData))) {
                    $return_data = array(
                        'status'    => true,
                        'message'   => 'Document information has been saved successfully.',
                        'id'        => $ID,
                        'data'      => $insertData
                    );

                    // delete old logo if edited
                    if ($existingData !== false && isset($insertData['Logo'])) {
                        @unlink(LOGO_DIRECTORY . $existingData->Logo);
                    }

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

    /**
    * template editor
    */
    public function template($code)
    {
        $record = $this->mgovdb->getRowObject('Doc_Templates', $code, 'Code');
        if ($record) {

            $viewData = array(
                'pageTitle'         => $record->Name . ' Template Editor',
                'accountInfo'       => user_account_details(),
                'documentData'      => (array) $record,
                'jsModules'         => array(
                    'utils',
                    'documents'
                ),
            );

            // Prepare extra fields
            unset($viewData['documentData']['ExtraFields']);
            $viewData['extraFields'] = prepare_document_extra_fields($record->ExtraFields);

            if (get_post('document_template')) {
                $updateData = array(
                    'id'         => $record->id,
                    'UpdatedBy'  => current_user(),
                    'LastUpdate' => date('Y-m-d H:i:s'),
                    'Content'    => get_post('document_template')
                );
                if ($this->mgovdb->saveData('Doc_Templates', $updateData)) {
                    $viewData['flash'] = 'Template has been updated successfully.';
                    $viewData['documentData']['Content'] = get_post('document_template');
                }
            }

            // print_data($viewData);

            view('pages/documents/template', $viewData, 'templates/mgovadmin');

        } else {
            redirect('documents');
        }
    }

    /**
    * save extra form field
    */
    public function save_extra_field()
    {
        $record = $this->mgovdb->getRowObject('Doc_Templates', get_post('Code'), 'Code');
        if ($record) {

            // save as json string

            if (validate('save_document_extra_field') == FALSE) {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Some fields have errors.',
                    'fields'    => validation_error_array()
                );
            } else {
                $currentFields = (array) @json_decode($record->ExtraFields, true);
                $newField = array(
                        'type'      => get_post('FieldType'),
                        'keyword'   => strtoupper(get_post('FieldKey')),
                        'label'     => get_post('FieldLabel'),
                    );
                if (get_post('id') && isset($currentFields[get_post('id')])) {
                    // edit
                    $id = get_post('id');
                    $currentFields[$id] = $newField;
                } else {
                    // add
                    $id = strtolower(random_letters(6));
                    $currentFields[$id] = $newField;
                }

                $updateData = array(
                    'id'         => $record->id,
                    'UpdatedBy'  => current_user(),
                    'LastUpdate' => date('Y-m-d H:i:s'),
                    'ExtraFields'=> json_encode($currentFields)
                );
                if ($this->mgovdb->saveData('Doc_Templates', $updateData)) {
                    $return_data = array(
                        'status'    => true,
                        'message'   => 'Field has been updated.',
                        'data'      => prepare_document_extra_fields(json_encode($currentFields))
                    );
                } else {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Saving extra field failed.'
                    );
                }
            }

        } else {
            $return_data = array(
                'status'    => false,
                'message'   => 'Invalid document code.'
            );
        }

        response_json($return_data);
    }

    /**
    * remove extra field
    */
    public function remove_extra_field($code, $id)
    {
        $record = $this->mgovdb->getRowObject('Doc_Templates', $code, 'Code');
        if ($record) {
            
            $currentFields = (array) @json_decode($record->ExtraFields, true);
            if (isset($currentFields[$id])) {
                
                unset($currentFields[$id]);

                $updateData = array(
                    'id'         => $record->id,
                    'UpdatedBy'  => current_user(),
                    'LastUpdate' => date('Y-m-d H:i:s'),
                    'ExtraFields'=> json_encode($currentFields)
                );
                if ($this->mgovdb->saveData('Doc_Templates', $updateData)) {
                    $return_data = array(
                        'status'    => true,
                        'message'   => 'Field has been removed.',
                        'data'      => prepare_document_extra_fields(json_encode($currentFields))
                    );
                } else {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Removing extra field failed.'
                    );
                }

            } else {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Invalid field id'
                );

            }
        } else {
            $return_data = array(
                'status'    => false,
                'message'   => 'Invalid document code.'
            );
        }

        response_json($return_data);
    }

    /**
    * delete docuement
    */
    public function delete($id)
    {
        if ($id) {
            $record = $this->mgovdb->getRowObject('Doc_Templates', $id, 'Code');
            if ($record) {
                $updateData = array(
                    'id'         => $record->id,
                    'deletedBy'  => current_user(),
                    'deletedAt'  => date('Y-m-d H:i:s')
                );
                if ($this->mgovdb->saveData('Doc_Templates', $updateData)) {
                    $return_data = array(
                        'status'    => true,
                        'message'   => 'Document template has been deleted.'
                    );
                } else {
                    $return_data = array(
                        'status'    => false,
                        'message'   => 'Deleting document template failed.'
                    );
                }
            } else {
                $return_data = array(
                    'status'    => false,
                    'message'   => 'Invalid document template.'
                );
            }
        } else {
            $return_data = array(
                'status'    => false,
                'message'   => 'Invalid document template.'
            );
        }

        response_json($return_data);
    }


    /**
    * preview template on pdf
    */
    public function template_preview($type = 'pdf', $code = null)
    {
        if ($code) {
            $content = '';
        } else {
            $content = get_post('template_html');
        }

        if ($content != '') {

            $filename = "Template Preview";

            if ($type == 'pdf') {

                $mpdf = new \Mpdf\Mpdf(array('format' => 'Letter', 'mode' => 'utf-8'));

                $mpdf->SetWatermarkText('TEMPLATE');
                $mpdf->showWatermarkText = true;

                $content = str_replace("\xc2\xa0",'&nbsp;',$content);

                $mpdf->WriteHTML($content);
                
                // overwrite html title
                $mpdf->SetTitle($filename);
                $mpdf->SetSubject($filename);
                $mpdf->SetAuthor('MGOVPH');
                $mpdf->SetCreator('MGOVPH');

                $mpdf->Output($filename . '.pdf', 'I');

            } else if ($type == 'html') {
                echo '<html><head><title>' . $filename . '</title></head><body style="min-width:816px;max-width:816px;">'.$content.'</body></html>';
            }

        } else {
            die('Invalid content.');
        }
    }

}