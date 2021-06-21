<?php 
require_once('stripe/Plan.php');
require_once('stripe/Product.php');
require_once('stripe/Subscription.php');
require_once('stripe/Customer.php');
require_once('stripe/Charge.php');

$pk = 'pk_test_51IgRUHGxRtOk5p73xvkJ7Yaph9z1qXHasbDacvh8Yy3ZyKgQxiRSEQwyvJk1YLroBJMIg3Ew3Q55UnkNTew2SwV700zjklJRbw';

$trial_end = strtotime(date('Y-m-d', strtotime("+7 day")));
$billing_cycle_anchor = strtotime(date('Y-m-d', strtotime("+8 day")));
$trial_amount = 100; //cent currency
$currency = 'usd';

$result = '';
// $product->create(['name' => 'Daily Subscription']);
// $product->create(['name' => 'Weekly Subscription']);
// $product->create(['name' => 'Monthly Subscription']);
// $product->create(['name' => 'Yearly Subscription']);

// echo '<pre>';
// print_r($product->all(4));
// echo '</pre>';

$plan = new Plan();

$dayPlanData =
    [
		'amount' => 50,
		'currency' => 'usd',
		'interval' => 'day',
		'product' => 'prod_JiAd1wX7XWtfZ3',
	];


// $plan->create($dayPlanData);

$weekPlanData = 
    [
		'amount' => 100,
		'currency' => 'usd',
		'interval' => 'week',
		'product' => 'prod_JiAfC1ePkT03vW',
	];

// $plan->create($weekPlanData);

$monthPlanData = 
    [
		'amount' => 150,
		'currency' => 'usd',
		'interval' => 'month',
		'product' => 'prod_JiAamfc5uX1UUT',
	];

// $plan->create($monthPlanData);

$yearPlanData = 
    [
		'amount' => 200,
		'currency' => 'usd',
		'interval' => 'year',
		'product' => 'prod_JiAaSc8qyz1r07',
	];

// $plan->create($yearPlanData);

$planList = $plan->all(100) ; 
// echo '<pre>';
// print_r($plan->all(100));
// echo '</pre>';

if(!empty($_POST['Add_PLAN'])){
	$plan = new Plan();
	$planData = 
    [
		'amount' => $_POST['amount']*100,
		'currency' => $_POST['currency'],
		'interval' => $_POST['interval'],
		'product' => $_POST['product_id'],
	];
	$result = $plan->create($planData);
}

if(!empty($_POST['Add_PRODUCT'])){
	$product = new Product();
	$result = $product->create(['name' => $_POST['product_name']]);
}

if(!empty($_POST['token'])){ 

	$customer = new Customer();
	$customerData = [
		'name'  => $_POST['firstname'] . ''. $_POST['lastname'],
		'email' => $_POST['email'], 
		'source' => $_POST['token'],
	];
	$customer = $customer->create($customerData);

	
	$plainId = $_POST['plan_id'];

	$trial_end = strtotime(date('Y-m-d', strtotime("+7 day")));
	$billing_cycle_anchor = strtotime(date('Y-m-d', strtotime("+8 day")));

	$customerId = $customer->id;

	$subscription = new Subscription();

	$subscriptionData = [
		"customer" => $customerId, 
		"items" => [ 
			[
				"plan" => $plainId, 
			], 
		],
		// 'default_payment_method' => $payment_method_id,
		'trial_end' => $trial_end,
		'billing_cycle_anchor' => $billing_cycle_anchor, 
	];
	$charge = new Charge();

	$singlePaymentData = [
		"amount"        => $trial_amount, // required
		"currency"        => $currency, // required
		"source"        => $_POST['token'],
		"description" =>  'For Trial Period',
	];

	$singlePayment =  $charge->create($singlePaymentData);
	$subscription = $subscription->create($subscriptionData);

	$result = $subscription;
	// echo '<pre>';
	// print_r($singlePayment);
	// echo '</pre>';
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


	<div class="container-custom container" style="margin-top: 50px;">
		<div class="row">
		<div class="col-md-7 col-sm-6 col-xs-12">
			<div class="">
				<h3 class="font-weight-bold d-inline">Result</h3>
				<span class="float-right">
					<button type="button" data-toggle="modal" data-target="#add-product" class="btn btn-dark">Add Product</button> &nbsp;
			        <button type="button" data-toggle="modal" data-target="#add-plan" class="btn btn-dark">Add Plan</button>
			     </span>
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
	         		<label>Select Plan</label>
					 <?php //echo '<pre>'; print_r($planList->data); echo '</pre>'; ?>

	         		<select name="plan_id" class="form-control">
	         			<option selected="" value="">Select</option>
						 <?php foreach($planList->data as  $plan){ 
							 
							 $product = new Product();
							 $product = $product->retrieve($plan->product);
							 
							 ?>
                        	<option value="<?php echo $plan->id; ?>"><?php echo $product->name.' [$'.($plan->amount/100).'/'.$plan->interval.']'; ?></option>
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


	<!-- add item modal -->
	<div class="modal fade" id="add-product" tabindex="-1" role="dialog">
	  <div class="modal-dialog modal-dialog-centered" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLongTitle">Add Product</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
		  <form action="" method="POST">
			<div class="modal-body">
					<div class="form-group">
						<label>Product Name</label>
						<input type="text" class="form-control" name="product_name" id="product_name">
					</div>
			</div>
			<div class="modal-footer">
				<button type="submit" value="1" name="Add_PRODUCT" class="btn btn-primary">Save</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		  </form>
	    </div>
	  </div>
	</div>
	<!-- end add item modal -->

	<!-- add plan modal -->
	<div class="modal fade" id="add-plan" tabindex="-1" role="dialog">
	  <div class="modal-dialog modal-dialog-centered" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLongTitle">Add Plan</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
		  <form action="" method="POST">
	      <div class="modal-body">
	         
	         	<div class="form-group">
	         		<label>Product Name</label>
	         		<select name="product_id" class="form-control">
	         			<option selected="" value="">Select</option>
						 <?php  
						 		$products = '';
						 		$product = new Product(); 
						 		$products = $product->all(10);

								 foreach($products->data as $product){
						 ?>
	         					<option value="<?php echo $product->id; ?>"><?php echo $product->name; ?></option>
						 <?php } ?>
	         		</select>
	         	</div>

	         	<div class="form-group">
	         		<label>Price</label>
	         		<input type="text" class="form-control" name="amount" id="amount">
	         	</div>

	         	<div class="form-group">
	         		<label>Currency</label>
	         		<select name="currency" class="form-control">
	         			<option selected="" value="">Select</option>
	         			<option value="usd">USD</option>
	         		</select>
	         	</div>

	         	<div class="form-group">
	         		<label>Interval</label>
	         		<select name="interval" class="form-control">
	         			<option selected="" value="">Select</option>
	         			<option value="day">Day</option>
	         			<option value="week">Week</option>
	         			<option value="month">Month</option>
	         			<option value="year">Year</option>
	         		</select>
	         	</div>
	         
	      </div>
	      <div class="modal-footer">
		  	<button type="submit" value="1" name="Add_PLAN" class="btn btn-primary">Save</button>
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	      </div>
		  </form>
	    </div>
	  </div>
	</div>
	<!-- end plan modal -->
  <!-- bootstrap core js  -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>



  <!-- stripe js -->
  <script type="text/javascript">
  	var stripe = Stripe('<?php echo $pk; ?>');
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