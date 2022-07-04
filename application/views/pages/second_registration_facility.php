<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Finish Registration</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/smart_wizard.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/smart_wizard_theme_arrows.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/second_registration_facility.css'); ?>">
</head>
<style>	
	
</style>
<body>
	<div class="container">
        <!-- External toolbar sample -->
       

        <!-- SmartWizard html -->
        <div id="smartwizard" class="row">
            <ul>
                <li><a href="#step-1">Add Email Address</a></li>
                
            </ul>

            <div>
                <div id="step-1" class="">
                    <h3 class="border-bottom border-gray pb-2 smartwizard-heading">Add Email Address</h3>
                    <p>Your Registration Is Nearly Complete, please add an email address.</p>
                    <form class="email-form col-sm-5">
                    	<div class="form-group">
                    		<input type="email" class="form-control" placeholder="Enter Email Address..." id="email" >
                    		<!-- <span style="font-style: italic; color: red;">* Note: This Is Optional</span> -->
                    	</div>
                    </form>
                </div>
               
            </div>
        </div>


    </div>

<script
src="https://code.jquery.com/jquery-3.3.1.min.js"
integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
crossorigin="anonymous"></script>

<!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="<?php echo base_url('assets/js/jquery.smartWizard.min.js') ?>"></script>
<script>
	$(document).ready(function(){

           $(".email-form").submit(function(evt){
           		evt.preventDefault();
           });

            // Toolbar extra buttons
            var btnFinish = $('<button></button>').text('Verify')
                                             .addClass('btn btn-info')
                                             .on('click', function(){ alert('Finish Clicked'); });
            var btnSkip = $('<button></button>').text('Skip')
                                             .addClass('btn btn-success')
                                             .on('click', function(){
                                             	window.location.replace("<?php echo site_url('onehealth/index/'.$health_facility_slug.'/admin') ?>")
                                             });


            // Smart Wizard
            $('#smartwizard').smartWizard({
                    selected: 0,
                    theme: 'arrows',
                    keyNavigation : false,
                    transitionEffect:'slide',
                    showStepURLhash: true,
                    toolbarSettings: {
                    				  showNextButton : false,
                    				  showPreviousButton : false,
                                      toolbarButtonPosition: 'end',
                                      toolbarExtraButtons: [btnFinish,btnSkip]
                                    }
            });


            // External Button Events
            
            // Set selected theme on page refresh
            
        });
</script>
</body>

</html>