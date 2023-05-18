<div class="box"><!-- box Starts -->

<?php

$session_email = $_SESSION['customer_email'];

$select_customer = "select * from customers where customer_email='$session_email'";

$run_customer = mysqli_query($con,$select_customer);

$row_customer = mysqli_fetch_array($run_customer);

$customer_id = $row_customer['customer_id'];
$customer_name = $row_customer['customer_name'];



?>

<!-- <h1 class="text-center">Payment Options For You</h1>

<p class="lead text-center">

<a href="order.php?c_id=<?php echo $customer_id; ?>">Pay Off line</a> -->

</p>

<!-- <center> -->
  <!-- center Starts -->
  <input type="button" style="width: 50%;" name="submit" id="btn" value="Pay" onclick="pay_now()"/>

<!-- <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
  <input type="hidden" name="cmd" value="_s-xclick">
  <input type="hidden" name="hosted_button_id" value="9PWJZYVQH8KGU">
  <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
  <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
  </form> -->


<?php

$i = 0;


$ip_add = getRealUserIp();

$get_cart = "select * from cart where ip_add='$ip_add'";

$run_cart = mysqli_query($con,$get_cart);

while($row_cart = mysqli_fetch_array($run_cart)){

$pro_id = $row_cart['p_id'];

$pro_qty = $row_cart['qty'];

$pro_price = $row_cart['p_price'];

$get_products = "select * from products where product_id='$pro_id'";

$run_products = mysqli_query($con,$get_products);

$row_products = mysqli_fetch_array($run_products);

$product_title = $row_products['product_title'];

$i++;

?>


<input type="hidden" id="food" name="item_name_<?php echo $i; ?>" value="<?php echo $product_title; ?>" >

<input type="hidden" id="contact" name="item_number_<?php echo $i; ?>" value="<?php echo $i; ?>" >

<input type="hidden" id="amt" name="amount_<?php echo $i; ?>" value="<?php echo $pro_price; ?>" >

<input type="hidden" id="qty" name="quantity_<?php echo $i; ?>" value="<?php echo $pro_qty; ?>" >
<input type="hidden" id="name" name="<?php echo $customer_name = $row_customer['customer_name']; ?>" value="<?php echo $customer_name = $row_customer['customer_name']; ?>" >





<?php } ?>

<!-- <input type="image" name="submit" width="500" height="270" src="images/paypal.png" > -->


</form><!-- form Ends -->

<!-- center Ends -->

</div><!-- box Ends -->

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
function pay_now(){
    var name=jQuery('#name').val();
    var amt=jQuery('#amt').val();
    var qty=jQuery('#qty').val();
    let food=jQuery('#food').val();
    let  contact=jQuery('#contact').val();
    let email=jQuery('#email').val();
    let address =jQuery('#address').val();
    let ind=jQuery('#ind').val();
        let total=amt * qty;
        console.log(ind);
     jQuery.ajax({
           type:'post',
           url:'payment_process.php?c_id=<?php echo $customer_id; ?>',
           data:"amt="+amt+"&name="+name+"&qty="+qty,
           success:function(result){
               var options = {
                    "key": "rzp_test_5GuRa5hNoNWijR", 
                    "amount": qty*amt*100, 
                    "currency": "INR",
                    "name": "Acme Corp",
                    "description": "Test Transaction",
                    "image": "https://image.freepik.com/free-vector/logo-sample-text_355-558.jpg",
                    "handler": function (response){
                       jQuery.ajax({
                           type:'post',
                           url:'./payment_process.php?c_id=<?php echo $customer_id; ?>',
                           data:"payment_id="+response.razorpay_payment_id+"amt="+amt+"&name="+name+"&qty="+qty+"&email="+email+"&food="+food+"&contact="+contact+"&address="+address+"&price="+total+"&in="+ind,
                           success:function(result){
                         
                            window.location.href = "customer/my_account.php?my_orders";
                           }
                       });
                    }
                };
                var rzp1 = new Razorpay(options);
                rzp1.open();
           }
       });
    
    
}
</script>