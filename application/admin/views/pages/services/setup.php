<div class="row">
	<div class="col-xs-12">
		
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title" style="display: block;">
					Basic Informations
					<b class="pull-right text-red">23123123123</b>
				</h3>
			</div>
			<!-- /.box-header -->
			<!-- form start -->
			<form role="form">
				<div class="box-body">
					<div class="row">
						<div class="col-sm-12 col-md-6">
							<div class="form-group">
								<label for="Name">Name</label>
								<input type="text" class="form-control" id="Name" placeholder="Service name">
							</div>
							<div class="form-group">
								<label for="ServiceType">Service Type</label>
								<select class="form-control" name="ServiceType" id="ServiceType">
									<option value="">--</option>
									 <?php
					                  foreach (lookup('service_type') as $k => $v) {
					                    echo "<option value='{$k}'>{$v}</option>";
					                  }
					                ?>
								</select>
							</div>
							<div class="form-group">
								<label for="LocationScope">Location Scope</label>
								<select class="form-control" name="LocationScopeID" id="LocationScope">
									<option value="">--</option>
									 <?php
					                  foreach (lookup('location_scope') as $k => $v) {
					                    echo "<option value='{$k}'>{$v}</option>";
					                  }
					                ?>
								</select>
							</div>
						</div>
						<div class="col-sm-12 col-md-6">
							<div class="form-group">
								<label for="Description">Description</label>
								<textarea class="form-control" name="Description" id="Description"></textarea>
							</div>
							<div class="form-group">
								<label for="TermsCondition">Terms and Condition</label>
								<textarea class="form-control" name="TermsCondition" id="TermsCondition"></textarea>
							</div>
							<div class="form-group">
								<label for="Objectives">Objectives</label>
								<textarea class="form-control" name="Objectives" id="Objectives"></textarea>
							</div>
							<div class="form-group">
								<label for="Qualifications">Qualifications</label>
								<textarea class="form-control" name="Qualifications" id="Qualifications"></textarea>
							</div>
						</div>
					</div>
				</div>
				<!-- /.box-body -->
				<div class="box-footer">
					<button type="submit" class="btn btn-primary">Save Info</button>
				</div>
			</form>
		</div>

	</div>
</div>