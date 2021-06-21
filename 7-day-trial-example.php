<?php 
require_once('stripe/Curl.php');
require_once('stripe/Plan.php');
require_once('stripe/Product.php');
require_once('stripe/Subscription.php');
require_once('stripe/Customer.php');
require_once('stripe/Charge.php');

$curl = new Curl();
(new LoadDotEnvFile($curl->getEnvFilePath('../.env'), []))->loadEnv();


$pk = $_ENV['STRIPE_PUBLISHABLE_KEY'];

$trial_end = strtotime(date('Y-m-d', strtotime("+7 day")));
$billing_cycle_anchor = strtotime(date('Y-m-d', strtotime("+8 day")));
$trial_amount = 100; //cent currency
$currency = 'usd';

$result = '';

$plan = new Plan();
$planList = $plan->all(1) ; 

if(!empty($_POST['token'])){ 

	$customer = new Customer();
	$customerData = [
		'name'  => $_POST['firstname'] . ''. $_POST['lastname'],
		'email' => $_POST['email'], 
		// 'source' => $_POST['token'],
	];
	$customer = $customer->create($customerData);
	$plainId = $_POST['plan_id'];

	$customerId = $customer->id;

	$charge = new Charge();

	$singlePaymentData = [
		"amount"        => $trial_amount, // required
		"currency"        => $currency, // required
		"source"        => $_POST['token'],
		"description" =>  'For trial period',
	];

	$singlePayment =  $charge->create($singlePaymentData);

	$result = $singlePayment;
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Stripe Payment</title>

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
	<style type="text/css">

		* {
		  font-family: "Helvetica Neue", Helvetica;
		  font-size: 15px;
		  font-variant: normal;
		  padding: 0;
		  margin: 0;
		}

		html {
		  height: 100%;
		}

		body {
		  background: #E6EBF1;
		  display: flex;
		  align-items: center;
		  justify-content: center;
		  min-height: 100%;
		}

		form {
		  width: 100%;
		  margin: 20px 0;
		}

		.group {
		  background: white;
		  box-shadow: 0 7px 14px 0 rgba(49, 49, 93, 0.10), 0 3px 6px 0 rgba(0, 0, 0, 0.08);
		  border-radius: 4px;
		  margin-bottom: 20px;
		}

		label {
		  position: relative;
		  color: #8898AA;
		  font-weight: 300;
		  height: 40px;
		  line-height: 40px;
		  display: flex;
		  flex-direction: row;
		}

		.group label:not(:last-child) {
		  border-bottom: 1px solid #F0F5FA;
		}

		label > span {
		  width: 120px;
		  text-align: right;
		  margin-right: 30px;
		}

		.field {
		  background: transparent;
		  font-weight: 300;
		  border: 0;
		  color: #31325F;
		  outline: none;
		  flex: 1;
		  padding-right: 10px;
		  padding-left: 10px;
		  cursor: text;
		}

		.field::-webkit-input-placeholder {
		  color: #CFD7E0;
		}

		.field::-moz-placeholder {
		  color: #CFD7E0;
		}

		form button {
		  float: left;
		  display: block;
		  background: #666EE8;
		  color: white;
		  box-shadow: 0 7px 14px 0 rgba(49, 49, 93, 0.10), 0 3px 6px 0 rgba(0, 0, 0, 0.08);
		  border-radius: 4px;
		  border: 0;
		  margin-top: 20px;
		  font-size: 15px;
		  font-weight: 400;
		  width: 100%;
		  height: 40px;
		  line-height: 38px;
		  outline: none;
		}

		button:focus {
		  background: #555ABF;
		}

		button:active {
		  background: #43458B;
		}

		.outcome {
		  float: left;
		  width: 100%;
		  padding-top: 8px;
		  min-height: 24px;
		  text-align: center;
		}

		.success,
		.error {
		  display: none;
		  font-size: 13px;
		}

		.success.visible,
		.error.visible {
		  display: inline;
		}

		.error {
		  color: #E4584C;
		}

		.success {
		  color: #666EE8;
		}

		.success .token {
		  font-weight: 500;
		  font-size: 13px;
		}

		.img-thumbnail{
			width: 50px;
			height: 50px;
			object-fit: cover;
			margin-right: 10px;
		}
		.mt-20{margin-top: 20px;}
		.container-custom{
			max-width: 80%;
			margin: auto
		}

		.form-group label{
			 height: auto !important; 
           line-height: unset !important;
		}

	</style>


	<script src="https://js.stripe.com/v3/"></script>
</head>
<body>


	<div class="container-custom container">
		<div class="row">
		<div class="col-md-7 col-sm-6 col-xs-12">
			<div class="">
				<h3 class="font-weight-bold d-inline">Result</h3>
				
			</div>
			<div class="w-100">

				<?php if($result){ echo '<pre>'; print_r($result); echo '</pre>'; } ?>
			</div>
		</div>	
		<div class="col-md-5 pl-5 col-sm-5 col-xs-12 ">
			<h3 class="font-weight-bold">Stripe Payment</h3>
			<!-- <form action="//httpbin.org/post" method="POST"> -->
			<form action="" method="POST" id="paymentFrm">
			    <input type="hidden" name="token" />

				<div class="form-group">
	         		<label>Plan</label>
					 <?php //echo '<pre>'; print_r($planList->data); echo '</pre>'; ?>

	         		<select name="plan_id" class="form-control">
	         			<!-- <option selected="" value="">Select</option> -->
						 <?php foreach($planList->data as  $plan){ 
							 
							 $product = new Product();
							 $product = $product->retrieve($plan->product);
							 
							 ?>
                        	<option selected value="<?php echo $plan->id; ?>"><?php echo $product->name
							//. ' [$'.($plan->amount/100).'/'.$plan->interval.']';
							 ?></option>
                    	<?php } ?>
	         		</select>
	         	</div>
				
			     <div class="group">
			      <label>
			        <span>First name</span>
			        <input id="firstname" name="firstname" class="field" placeholder="" />
			      </label>
			      <label>
			        <span>Last name</span>
			        <input id="lastname" name="lastname" class="field" placeholder="" />
			      </label>
			    </div>

				<div class="group">
			      <label>
			        <span>Email</span>
			        <input id="email" name="email" class="field" placeholder="" />
			      </label>
			    </div>

			    <div class="group">
			      <label>
			        <span>Name on Card</span>
			        <input id="name_on_card" name="name_on_card" class="field" placeholder="Name on Card" />
			      </label>
			      <label>
			        <span>Card</span>
			        <div id="card-element" class="field"></div>
			      </label>
			    </div>
			   <label style="color: red; text-align: center; height: 13px; margin-left: 35%; font-weight: bold;">$1.00 paid 7-day trial</label>
			    <button type="submit">Pay</button>
				<div class="outcome">
			      <div class="error"></div>
			      <div class="success">
			        Success! 
					<!-- Your Stripe token is <span class="token"> -->

					</span>
			      </div>
			    </div>
			  </form>
		</div>	
	</div>

	</div>


  <!-- bootstrap core js  -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>



  <!-- stripe js -->
  <script type="text/javascript">
  	var stripe = Stripe("<?php echo $pk; ?>");
	var elements = stripe.elements();

	var card = elements.create('card', {
	  hidePostalCode: true,
	  style: {
	    base: {
	      iconColor: '#666EE8',
	      color: '#31325F',
	      lineHeight: '40px',
	      fontWeight: 300,
	      fontFamily: 'Helvetica Neue',
	      fontSize: '15px',

	      '::placeholder': {
	        color: '#CFD7E0',
	      },
	    },
	  }
	});
	card.mount('#card-element');

	function setOutcome(result) {
	  var successElement = document.querySelector('.success');
	  var errorElement = document.querySelector('.error');
	  successElement.classList.remove('visible');
	  errorElement.classList.remove('visible');

	  if (result.token) {
	    // In this example, we're simply displaying the token
	    // successElement.querySelector('.token').textContent = result.token.id;
	    // successElement.classList.add('visible');

	    // In a real integration, you'd submit the form with the token to your backend server
	    var form = document.querySelector('form');
	    form.querySelector('input[name="token"]').setAttribute('value', result.token.id);
	    form.submit();
	  } else if (result.error) {
	    errorElement.textContent = result.error.message;
	    errorElement.classList.add('visible');
	  }
	}

	card.on('change', function(event) {
	  setOutcome(event);
	});

	document.querySelector('form').addEventListener('submit', function(e) {
	  e.preventDefault();
	  var options = {
	    name: document.getElementById('firstname').value + " " + document.getElementById('lastname').value,
	    // address_line1: document.getElementById('address-line1').value,
	    // address_line2: document.getElementById('address-line2').value,
	    // address_city: document.getElementById('address-city').value,
	    // address_state: document.getElementById('address-state').value,
	    // address_zip: document.getElementById('address-zip').value,
	    // address_country: document.getElementById('address-country').value,
	  };
	  stripe.createToken(card, options).then(setOutcome);
	});

  </script>
</body>
</html>