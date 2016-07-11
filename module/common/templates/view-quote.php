

<div class="product">
  <div class="product_1"></div>
  <div class="product_2">View 
    <?php echo strtoupper($module);?>
  </div>
  <div class="product_3"></div>
</div>
<!--End of product-->
<div class="task">
  <div class="task_1_add">&nbsp;&nbsp;&nbsp;</div>
  <div class="task_2_add">
    <div class="name_add"></div>
    <div class="name_1_add">
      
				<div class="programing_solution">
          <div class="our_service_left"></div>
          
          <div class="our_service_right">&nbsp;&nbsp;</div>
          <div class="demo_body">
            <div class="portfolio_box" onmouseout="this.style.backgroundColor=''" onmouseover="this.style.backgroundColor='#f7f8f9'" style="cursor:pointer;">			
             <?php 
              //echo  $msg;	
              if ( !empty($err) || !empty($msg) ): ?>
              
              <div class="">
                <div align="center">
                  <div class="<?php echo ( !empty($err)?'err_div':'msg_div');?>" > <?php echo  ( !empty($err)?$err:$msg); ?> </div>
                </div>
              </div>
              
              <?php endif?>
              <div class="quote_block_wrap" style="font-size:11px;">
                <div class="quote_block">
                  <div class="input_block" style="margin-top:10px;">
                    <label class="contact_label"> Contact Name:*</label>
                    
                    <br />
                    <?php echo $data['contactname']?>
                    
                  </div>
                  <div class="input_block">
                    <label class="contact_label"> Company Name:*</label>
                    
                    <br />
                    <?php echo $data['companyname']?>
                    
                  </div>
                  <div class="input_block">
                    <label class="contact_label"> Email:*</label>
                    
                    <br />              
                    <?php echo $data['email']?>
                  </div>
                  <div class="input_block">
                    <label class="contact_label">Service:*</label>
                    
                    <br />
                    <?php echo $data['select_service']?>
                  </div>
                  <div class="input_block">
                    <label class="contact_label">Budget:*</label>
                    
                    <br />
                    <?php echo $data['budget']?>
                  </div>
                  <div class="input_block">
                    <label class="contact_label">Deadline:*</label>
                    
                    <br />
										<?php echo $data['deadline']?>
                  </div>
                  <div class="input_block">
                    <label class="contact_label"> Current Website url(if any):</label>
                    <br />
                    
                    <?php echo $data['current_site']?>
                  </div>
                  <div class="input_block">
                    <label class="contact_label"> URLs of the referrer websites (if any):</label>
                    <br />
                    <?php echo $data['match_site_1']?>
                    <br />
                    <?php echo $data['match_site_2']?>
                    <br />
										<?php echo $data['match_site_3']?>
                    <br />
										<?php echo $data['match_site_4']?>
                    
                  </div>
                </div>
                <!--Start Site Detaild-->
                <div class="site_details" id="site_details" >
                  <div class="quote_title">Details on your web site:</div>
                  <!--Start page section-->
                  <div class="quote_option_block" id="page_section">
                    <div class="quote_left"></div>
                    <div class="quote_option_tittle"><a href="#" onclick="toogleSection('option_inner_page'); return false;"> <span id="option_inner_page_sign">+</span> Pages, Contents, Animation:</a></div>
                    <div class="quote_right"></div>
                    <div class="quote_option_inner_block" id="option_inner_page" >
                      <table>
                        <tr>
                          <td width="281" align="left">Website language/s:</td>
                          <td width="357" align="left"><?php echo $data['languages']?></td>
                        </tr>
                        <tr>
                          <td align="left">Approximate number of pages:</td>
                          <td align="left"><?php echo $data['page_numbers']?></td>
                        </tr>
                        <tr>
                          <td align="left">What are the main sections of the website?</td>
                          <td align="left"><?php echo $data['main_sections']?></td>
                        </tr>
                        <tr>
                          <td align="left">Do you need flash as header or menu?</td>
                          <td align="left"><?php echo $data['flash_header']?></td>
                        </tr>
                        <tr>
                          <td align="left">Do you need a Full Flash web site?</td>
                          <td align="left"><?php echo $data['full_flash']?></td>
                        </tr>
                        <tr>
                          <td align="left">Do you need flash intro?</td>
                          <td align="left"><?php echo $data['flash_intro1']?></td>
                        </tr>
                        <tr>
                          <td align="left">Do we need to work with and customize any template?</td>
                          <td align="left"><?php echo $data['template']?></td>
                        </tr>
                        <tr>
                          <td align="left">What images to use? Where can we find them?</td>
                          <td align="left"><?php echo $data['ref_images']?></td>
                        </tr>
                        <tr>
                          <td align="left">What text/copies to use? Where can we find them?</td>
                          <td align="left"><?php echo $data['ref_texts']?></td>
                        </tr>
                      </table>
                    </div>
                  </div>
                  <!--End page section-->
                  <!--Start Feature section-->
                  <div class="quote_option_block" id="feature_section">
                    <div class="quote_left"></div>
                    <div class="quote_option_tittle"><a href="#" onclick="toogleSection('feature_inner_section'); return false;" > <span id="feature_inner_section_sign">+</span> Dynamic Features and Programming:</a></div>
                    <div class="quote_right"></div>
                    <div class="quote_option_inner_block" id="feature_inner_section">
                      <table width="100%" cellspacing="0" cellpadding="0" border="0" class="quotetable toggle" style="display: block;">
                        <tbody>
                          <tr>
                            <td width="44%" align="left">Preferred server-side scripting language/s(if any):</td>
                            <td width="56%" align="left"><?php echo $data['scriptlanguage']?></td>
                          </tr>
                          <tr>
                            <td align="left">Do you need a CMS (content management system)? </td>
                            <td align="left"><?php echo $data['cms']?></td>
                          </tr>
                          <tr>
                            <td align="left">Do you need a  custom made CMS? (if open source, select CMS below)</td>
                            <td align="left"><?php echo $data['custom_open']?></td>
                          </tr>
                          <tr>
                            <td align="left">Do you need a shopping cart?</td>
                            <td align="left"><?php echo $data['shopping']?></td>
                          </tr>
                          <tr>
                            <td align="left">If you need  cart, will it be custom made (if open source, select below)? </td>
                            <td align="left"><?php echo $data['custom_open_cart']?></td>
                          </tr>
                          <tr>
                            <td align="left">Do we need to work with any existing database or script ?</td>
                            <td align="left"><?php echo $data['exixting_script']?></td>
                          </tr>
                          <tr>
                            <td align="left">If we need to work with an existing script,  specify URL of demo:</td>
                            <td align="left"><?php echo $data['demo_script']?></td>
                          </tr>
                          <tr>
                            <td align="left">Url to download the source code if existing script: </td>
                            <td align="left"><?php echo $data['download_source']?></td>
                          </tr>
                          <tr>
                            <td align="left">Provide details on your web site's dynamic requirements.</td>
                            <td align="left"><?php echo $data['dynamic_site_requirements']?></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <!--End Feature section-->
                  <!--Start Corporate section-->
                  <div class="quote_option_block" id="corporate_section">
                    <div class="quote_left"></div>
                    <div class="quote_option_tittle"><a href="#" onclick="toogleSection('corporate_inner_section'); return false;" > <span id="corporate_inner_section_sign">+</span> Corporate Identity Graphics Design:</a></div>
                    <div class="quote_right"></div>
                    <div class="quote_option_inner_block" id="corporate_inner_section">
                      <table width="100%" cellspacing="0" cellpadding="0" border="0" class="quotetable toggle" style="display: block;">
                        <tbody>
                          <tr>
                            <td width="286" align="left">Logo:</td>
                            <td width="38" align="left"><?php echo $data['logo']?></td>
                            <td width="116" align="left">Brochure:</td>
                            <td width="210" align="left"><?php echo $data['brochure']?></td>
                          </tr>
                          <tr>
                            <td width="286" align="left">Letterhead:</td>
                            <td width="38" align="left"><?php echo $data['letterhead']?></td>
                            <td width="116" align="left">Business card:</td>
                            <td width="210" align="left"><?php echo $data['business_card']?></td>
                          </tr>
                          <tr>
                            <td width="286" align="left">Flash presentation:</td>
                            <td width="38" align="left"><?php echo $data['flash_presentation']?></td>
                            <td width="116" align="left">Flash Intro:</td>
                            <td width="210" align="left"><?php echo $data['flash_intro']?></td>
                          </tr>
                          <tr>
                            <td align="left" colspan="1">Can you provide high quality pictures?</td>
                            <td align="left"><?php echo $data['corp_pictures']?></td>
                            <td colspan="2">&nbsp;</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <!--End Corporate section-->
                  <!--Start Template section-->
                  <div class="quote_option_block" id="template_section">
                    <div class="quote_left"></div>
                    <div class="quote_option_tittle"><a href="#" onclick="toogleSection('template_inner_section'); return false;" > <span id="template_inner_section_sign">+</span> Template or CMS customization:</a></div>
                    <div class="quote_right"></div>
                    <div class="quote_option_inner_block" id="template_inner_section">
                      <table width="100%" cellspacing="0" cellpadding="0" border="0" class="quotetable toggle" style="display: block;">
                        <tbody>
                          <tr>
                            <td width="44%" align="left">URL of the template your purchased(if any):</td>
                            <td width="56%"><?php echo $data['template_url']?></td>
                          </tr>
                          <tr>
                            <td align="left">What script or CMS you want to customize?</td>
                            <td align="left"> Wordpress
                              <?php echo $data['opensource']?>
                             	<?php echo $data['cms_customization_other']?></td>
                          </tr>
                          <tr>
                            <td align="left">Do we need to provide unique design for cms? </td>
                            <td align="left"><?php echo $data['unique_design_cms']?></td>
                          </tr>
                          <tr>
                            <td colspan="2"><em>(please also fill up the static or flash based web site section  above as appropiate) </em></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <!--End Template section-->
                </div>
                <!--End Site Detaild-->
                <div class="site_details">
                  <div class="describe_goal">Please describe the goal of the project &amp; products/services you will be offering. Include any other special requirements, not mentioned above:</div>
                  <div class="input_block" style="margin-top:10px;">
                    <?php echo $data['quote_describe_goal']?>
                    
                  </div>
                  <div class="input_block" style="margin-top:10px;">
                    <label class="contact_label"> How did you find us?:</label>
                    <br />
                    <?php echo $data['how_find']?>
                  </div>
                    
                  
                </div>
              </div>
              <!--End of demo_text-->
              <!--End of priveous-->
            </div>
          </div>
          <!--End of demo_body-->
          <div class="demo_body_bottom_left"><img src="/annanovas/images/demo_bottom_left.gif" width="8" height="19" border="0" alt="images" /></div>
          <div class="demo_body_bottom_right"></div>
        </div>

    
    </div>
    <!--End of name_1-->
    <div class="name_2">&nbsp;&nbsp;&nbsp;</div>
    <!--<div class="flash_8"></div>-->
  </div>
  <!--End of task_2-->
  <div class="bottom">&nbsp;&nbsp;&nbsp;</div>
</div>

