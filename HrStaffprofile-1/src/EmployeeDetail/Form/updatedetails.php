iv class="x_panel">
                    <div class="x_title">
                      <h2><?php echo $this->escapeHtml("Edit Staff Address Details"); ?></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                    </div>

                    <div class="x_content">
                      <?php 
                      $detailForm->setAttribute('action', $this->url('editemppersonaldetails', array('action' => 'editEmployeePersonalDetails', 'id' => my_encrypt($this->id, $key))));
                      $detailForm->prepare();
                      echo $this->form()->openTag($detailForm);
                      $details = $detailForm->get('employeedetail');
                      $details->get('id')->setValue($this->employeeDetails->getId());
                      $details->get('emp_id')->setValue($this->employeeDetails->getEmp_Id());
                      $details->get('first_name')->setValue($this->employeeDetails->getFirst_Name());
                      $details->get('middle_name')->setValue($this->employeeDetails->getMiddle_Name());
                       $details->get('last_name')->setValue($this->employeeDetails->getLast_Name());
                       $details->get('date_of_birth')->setValue(date('m/d/y', strtotime($this->employeeDetails->getDate_Of_Birth())));
                        $details->get('gender')->setValue($this->employeeDetails->getGender());
                         $details->get('marital_status')->setValue($this->employeeDetails->getMarital_Status());
                         $details->get('emp_id')->setValue($this->employeeDetails->getEmp_Id());
                          $details->get('cid')->setValue($this->employeeDetails->getCid());
                           $details->get('country')->setValue($this->employeeDetails->getCountry());
                            $details->get('nationality')->setValue($this->employeeDetails->getNationality());
                             $details->get('phone_no')->setValue($this->employeeDetails->getPhone_No());
                              $details->get('email')->setValue($this->employeeDetails->getEmail());
                               $details->get('blood_group')->setValue($this->employeeDetails->getBlood_Group());
                                $details->get('religion')->setValue($this->employeeDetails->getReligion());

                                 $details->get('gender')->setValueOptions($this->gender);
                                 $details->get('marital_status')->setValueOptions($this->maritialStatus);
                                 $details->get('country')->setValueOptions($this->country);
                                 $details->get('nationality')->setValueOptions($this->nationality);
                                 $details->get('blood_group')->setValueOptions($this->bloodGroup);
                                 $details->get('religion')->setValueOptions($this->religion);

                                 $details->get('submit')->setValue('Update Personal Details');
                                  echo $this->formHidden($details->get('id'));
                      ?>

                      <div class="row">
                        <div class="form-group">  
                            <label class="control-label col-md-2 col-sm-2 col-xs-12">First Name:</label>
                           <div class="col-md-2 col-sm-2 col-xs-12">
                              <?php echo $this->formRow($details->get('first_name')); ?>
                            </div>
                           <label class="control-label col-md-1 col-sm-3 col-xs-12">Middle Name:</label>
                           <div class="col-md-2 col-sm-2 col-xs-12">
                              <?php echo $this->formRow($details->get('middle_name')); ?>
                            </div>
                            <label class="control-label col-md-1 col-sm-2 col-xs-12">Last Name:</label>
                           <div class="col-md-2 col-sm-2 col-xs-12">
                              <?php echo $this->formRow($details->get('last_name')); ?>
                            </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group">  
                            <label class="control-label col-md-2 col-sm-2 col-xs-12">Date of Birth:</label>
                           <div class="col-md-2 col-sm-2 col-xs-12">
                              <?php echo $this->formRow($details->get('date_of_birth')); ?>
                            </div>
                            <label class="control-label col-md-1 col-sm-3 col-xs-12">Maritial Status:</label>
                               <div class="col-md-2 col-sm-2 col-xs-12">
                                  <?php echo $this->formRow($details->get('marital_status')); ?>
                                </div>
                                <label class="control-label col-md-1 col-sm-2 col-xs-12">Gender:</label>
                               <div class="col-md-2 col-sm-2 col-xs-12">
                                  <?php echo $this->formRow($details->get('gender')); ?>
                                </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="form-group">  
                            <label class="control-label col-md-2 col-sm-2 col-xs-12">CID:<br><small>(In case of non-national, plese enter work permit no.)</small></label>
                           <div class="col-md-2 col-sm-2 col-xs-12">
                              <?php echo $this->formRow($details->get('cid')); ?>
                            </div>
                            <label class="control-label col-md-1 col-sm-3 col-xs-12">Country:</label>
                               <div class="col-md-2 col-sm-2 col-xs-12">
                                  <?php echo $this->formRow($details->get('country')); ?>
                                </div>
                                <label class="control-label col-md-1 col-sm-2 col-xs-12">Nationality:</label>
                               <div class="col-md-2 col-sm-2 col-xs-12">
                                  <?php echo $this->formRow($details->get('nationality')); ?>
                                </div>
                        </div>
                      </div>

                      <div class="row">
                            <div class="form-group">  
                                <label class="control-label col-md-2 col-sm-2 col-xs-12">Phone No:</label>
                               <div class="col-md-2 col-sm-2 col-xs-12">
                                  <?php echo $this->formRow($details->get('phone_no')); ?>
                                </div>
                                <label class="control-label col-md-1 col-sm-2 col-xs-12">E-mail</label>
                               <div class="col-md-2 col-sm-2 col-xs-12">
                                  <?php echo $this->formRow($details->get('email')); ?>
                                </div>
                            </div>
                          </div>
                          <br />
                          <div class="row">
                            <div class="form-group">  
                                <label class="control-label col-md-2 col-sm-2 col-xs-12">Blood Group:</label>
                               <div class="col-md-2 col-sm-2 col-xs-12">
                                  <?php echo $this->formRow($details->get('blood_group')); ?>
                                </div>
                                <label class="control-label col-md-1 col-sm-2 col-xs-12">Religion:</label>
                               <div class="col-md-2 col-sm-2 col-xs-12">
                                  <?php echo $this->formRow($details->get('religion')); ?>
                                </div>
                                <label class="control-label col-md-1 col-sm-2 col-xs-12">Staff ID:</label>
                               <div class="col-md-2 col-sm-2 col-xs-12">
                                  <?php echo $this->formRow($details->get('emp_id')); ?>
                                </div>
                            </div>
                          </div>                     

                       <div class="ln_solid"></div>
                          <div class="row">
                            <div class="form-group">
                              <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <?php  
                                echo $this->formHidden($detailForm->get('csrf'));
                                echo $this->formSubmit($details->get('submit')); 
                                echo $this->form()->closeTag();
                                ?>
                                <a href="<?php echo $this->url('employeelist');?>"><span class="btn btn-primary">Back</span></a>
                              </div>
                            </div>
                          </div>
                    </div>
                  </div>