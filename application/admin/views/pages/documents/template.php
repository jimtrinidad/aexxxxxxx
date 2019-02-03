<div class="row">
  <div class="col-xs-12">
    <div class="row gutter-5">

      <div class="col-md-12 col-lg-5 col-lg-push-7">
        <div class="box box-success">
          <div class="box-header">
            <h3 class="box-title">Fields & Placeholders</h3>
            <div class="box-tools">
              <button type="submit" class="btn btn-sm btn-success" title="Add extra fields" onClick="Documents.addExtraField();"><i class="fa fa-plus"></i> Add Field</button>
            </div>
          </div>
          <div class="box-body small">
            <div id="extra-form-panel-cont"></div>
            <?php
              foreach (lookup('document_template_keywords') as $group) {
                if (!$documentData['SubDepartmentID'] && stripos($group['name'], 'Sub Department') !== false) {continue;}
                echo '<div class="panel panel-default">
                        <div class="panel-heading">' . $group['name'] . '</div>
                        <div class="panel-body">
                          <b>Keywords</b>
                          <div class="row">';
                        
                        foreach ($group['keys'] as $key => $label) {
                          echo '<div class="col-xs-6">';
                            echo ' { #'.$key.' } - <small>' . $label . '</small>';
                          echo '</div>';
                        }
                    echo '</div>';
                  if (isset($group['images']) && count($group['images'])) {
                    echo '<b>Images</b><div class="row">';
                    foreach ($group['images'] as $img) {
                        echo '<div class="col-xs-12">';
                          echo ' { '.public_url('get') . $img['url'].' } - <small>' . $img['name'] . '</small>';
                        echo '</div>';
                      }
                    echo '</div>';
                  }
                  echo '</div>';
                echo '</div>';
              }
            ?>
          </div>
        </div>
      </div>

      <div class="col-md-12 col-lg-7 col-lg-pull-5" style="overflow: auto;">
        <div class="box box-primary" style="min-width: 816px;max-width: 816px;margin: 0 auto;">
          <form action="" method="post" id="DocumentTemplateForm" name="DocumentTemplateForm" onsubmit="return confirm('Are you sure you want to save your changes?')">
            <div class="box-header">
              <h3 class="box-title">Editor</h3>
              <div class="box-tools">
                <button type="button" class="btn btn-sm btn-primary hidden previewButton" id="html_preview" title="Pdf preview" target="_blank"><i class="fa fa-code"></i> HTML Preview</button>
                <button type="button" class="btn btn-sm btn-primary hidden previewButton" id="pdf_preview" title="Pdf preview" target="_blank"><i class="fa fa-file-pdf-o"></i> PDF Preview</button>
              </div>
            </div>
            <div class="box-body">
              <?php 
              if (isset($flash)) {
                echo '<div class="alert alert-success alert-dismissible" role="alert">
                        <strong>Notice!</strong> '. $flash .'
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>';
              } 
              ?>
              <textarea id="document_template" name="document_template"><?php echo $documentData['Content'] ?></textarea>
            </div>
            <div class="box-footer">
              <button class="pull-right btn btn-sm btn-success"><i class="fa fa-save"></i> Save</button>
              <a href="<?php echo site_url('documents')?>" class="btn btn-sm btn-info"><i class="fa fa-arrow-left"></i> Back</a>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
</div>

<input type="hidden" id="document-code" value="<?php echo $documentData['Code'] ?>">
<?php view('pages/documents/modals.php'); ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.8.5/tinymce.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    Documents.extraFormFields = <?php echo json_encode($extraFields, JSON_HEX_TAG); ?>;
    Documents.setExtraFieldPanel();
  });
  tinymce.init({
      selector: 'textarea',
      height: 500,
      theme: 'modern',
      plugins: 'save print fullpage searchreplace autolink directionality visualblocks visualchars fullscreen image link media codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern code',
      toolbar1: 'formatselect fontselect fontsizeselect | bold italic underline strikethrough forecolor backcolor',
      toolbar2: 'undo redo | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent',
      toolbar3: 'save print paste | table link image | removeformat code',
      image_advtab: true,
      relative_urls: false,
      remove_script_host: false,
      content_css: [
        '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i'
      ],
      // image_list: [
      //   {title: 'My image 1', value: 'https://www.tinymce.com/my1.gif'},
      //   {title: 'My image 2', value: 'http://www.moxiecode.com/my2.gif'}
      // ],
      setup : function(ed) {
          ed.on('init', function(args) {
              $('.previewButton').removeClass('hidden');
          });
      }
  });
</script>