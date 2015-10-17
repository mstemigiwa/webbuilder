
<!--TODO DESIGN THis page looks awful -->
Total Recurring = <?php echo $this->Number->currency($vars['Cart']['Recurrring'],"NGN"); ?><br />
Total One Time = <?php echo $this->Number->currency($vars['Cart']['OneTime'],"NGN"); ?><br />
<script type="text/javascript" src="http://code.jquery.com/jquery-1.10.2.js"></script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
  Stripe.setPublishableKey('<?php echo Configure::read('Stripe.TestPublishableKey'); ?>');
</script>
<div id="payment_form">
<?php
echo $this->Form->create('Order', array('class' => 'payment-form','url' => array('controller' => 'orders', 'action' => 'stripe'))); //,array('url' => array('controller' => 'orders', 'action' => 'pay'))
echo '<span class="payment-errors" style="color:red"></span>'; 
echo $this->Form->input('Order.number', array('label' => 'Card Number','value'=>'4242 4242 4242 4242'));//TODO remove card details for live
echo $this->Form->input('Order.cvc', array('label' => 'CVC','value'=>'321')); //TODO remove card details for live
echo $this->Form->input('Order.exp-month', array('label' => 'Expiration Month (MM)','value'=>'10'));//TODO remove card details for live
echo $this->Form->input('Order.exp-year', array('label' => 'Expiration Year (YYYY)','value'=>'2016'));//TODO remove card details for live
echo $this->Form->input('Order.items',array('type'=>'hidden','value'=>json_encode($vars['Cart'])));//TODO remove card details for live
echo $this->Form->end('Pay');
?>
</div>
<div id="loading" style="text-align: center; display: none;">
	We are processing your payment, please do not refresh or close this page.
	<!--TODO add loading image-->
</div>
<script type="text/javascript">
jQuery(function($) {
  $('.payment-form').submit(function(e) {
    // Prevent the form from submitting with the default action
    e.preventDefault();
    var $form = $(this);
    // Disable the submit button to prevent repeated clicks
    $form.find('button').prop('disabled', true);
    $("#payment_form").hide();
    $("#loading").show();
    
    
    Stripe.card.createToken({
      number: $('#OrderNumber').val(),
      cvc: $('#OrderCvc').val(),
      exp_month: $('#OrderExp-month').val(),
      exp_year: $('#OrderExp-year').val()
    }, stripeResponseHandler);
  });
});

	var stripeResponseHandler = function(status, response) {
  var $form = $('.payment-form');
  if (response.error) {
    // Show the errors on the form
    $form.find('.payment-errors').text(response.error.message);
    $form.find('button').prop('disabled', false);
     $("#payment_form").show();
    $("#loading").hide();
    
  } else {
    // token contains id, last4, and card type
    var token = response.id;
    // Reset form data we do not want to submit to the server
    $('#OrderNumber, #OrderCvc, #OrderExp-month, #OrderExp-year').val("");
    // Insert the token into the form so it gets submitted to the server
    $form.append($('<input type="hidden" name="data[Order][stripeToken]" />').val(token));
    // and submit
    $form.get(0).submit();
  }
};
</script>