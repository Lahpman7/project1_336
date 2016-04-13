<html>
<head>
    <title align> Book Store </title>
</head>
    <h1 align="center">Welcome to the Book Store</h1>
    <h2 align="center">Added Cart Items will be displayed at the bottom of the page!</h2><br>
    <div align = "center">
<img src="http://www.friendsoflglibrary.org/wp-content/uploads/2012/07/bookstore-header.gif">
    </div><br>
<?php
    session_start();
    include_once("config.php");

?>
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link href='http://fonts.googleapis.com/css?family=Vollkorn:400,700' rel='stylesheet' type='text/css'>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<div align="center">
<select name = "selection">
  <option value="">Select Filter Here</option>
  <option value=1>Author</option>
  <option value=2>Category</option>
  <option value=3>Title</option>
</select>
<select name = "prie">
  <option value=0>Sort by Price Here</option>
  <option value=1>Price Ascending</option>
  <option value=2>Price Descending</option>
</select>
<div>
    <input type="submit" value="Filter"><br>
</div>
</form>
</div>
<div>
<?php
//filter sections
$sql = "SELECT Books.title, Categories.genre, Author.author, Description.info ,Books.price, Books.bid
FROM Books 
JOIN Categories ON (Books.cid = Categories.cid)
JOIN Author ON (Books.aid = Author.aid)
JOIN Description ON (Books.did = Description.did)";//default case here
if($_POST['selection']==1)
{
$sql = "SELECT Books.title, Categories.genre, Author.author, Description.info ,Books.price, Books.bid
FROM Books 
JOIN Categories ON (Books.cid = Categories.cid)
JOIN Author ON (Books.aid = Author.aid)
JOIN Description ON (Books.did = Description.did)
GROUP BY Author.author";
}
else if($_POST['selection']==2)
{
$sql = "SELECT Books.title, Categories.genre, Author.author, Description.info ,Books.price, Books.bid
FROM Books 
JOIN Categories ON (Books.cid = Categories.cid)
JOIN Author ON (Books.aid = Author.aid)
JOIN Description ON (Books.did = Description.did)
GROUP BY Categories.genre";}
else if($_POST['selection']==3)
{
$sql = "SELECT Books.title, Categories.genre, Author.author, Description.info, Books.price, Books.bid
FROM Books 
JOIN Categories ON (Books.cid = Categories.cid)
JOIN Author ON (Books.aid = Author.aid)
JOIN Description ON (Books.did = Description.did)
GROUP BY Books.title";}
else {
    $sql = $sql;
}
//Price ascending and descending additions (IF CLICKED!)
if($_POST['prie'] == 1)
{
    $sql .= ' ORDER BY price ASC';
}
else if($_POST['prie'] == 2)
{
    $sql .= ' ORDER BY price DESC';
}
else{$sql = $sql;}
//end filter of sections
$current_url = urlencode($url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);

$results = $mysqli->query($sql);
if($results){ 
$products_item = '';
//fetch results set as object and output HTML
echo '<div class="box-table" align="center"><table >
<th>Author</th>' . '<th>Title</th>' . '<th>Category</th>'. '<th>Description</th>'.'<th>Price</th>'. '<th>Add to Cart</th>';
while($obj = $results->fetch_object())
{
    global $descr;
    $descr = $obj->info;
    
$products_item .= <<<EOT
    <tr>
    <form method="post" action="cart_update.php">
    <td>{$obj->author}</td>
    <td>{$obj->title}</td>
    <td>{$obj->genre}</td>
    <td><input type="button" onclick='showHide()' value="Show Description"></td>
    <script type="text/javascript"> 
    
        function showHide(){
          window.alert('<?$obj->info;?>');
            
        }
    </script>
    <td>{$obj->price}</td>
    <td>
    
    <input type="text" size="2" maxlength="2" name="product_qty" value="1" />
    <input type="hidden" name="product_code" value="{$obj->bid}" />
    <input type="hidden" name="type" value="add"/>
    <input type="hidden" name="return_url" value="{$current_url}" />
    <div><button type="submit">Add</button></div>
    </td>

    </tr>
    
    </form>

EOT;
}

    echo $products_item;
}
?>
</div>
</table>
<div>
<h2>Your Shopping Cart</h2>
<?php //so it does delete, but it also clones tables need to delete...
if(isset($_SESSION["cart_products"]) && count($_SESSION["cart_products"])>0)
{
    echo '<div>';
    echo '<h3>Your Shopping Cart</h3>';
    echo '<form method="post" action="cart_update.php">';
    echo '<table width="50%" cellpadding="6" cellspacing="0">';
    echo '<tbody>';

    $total =0;
    foreach ($_SESSION["cart_products"] as $cart_itm)
    {
        $product_code = $cart_itm['product_code'];//FINALLY, needed to reference the array to take in NON global $product_code!!!!! 
        $product_name = $cart_itm["product_name"];//only to find out that we dont even have to remove items from cart... 
        $product_qty = $cart_itm["product_qty"];
        $product_price = $cart_itm["product_price"];
        if($product_name !=null){
        echo '<tr>';
        echo '<td>Qty <input type="text" size="2" maxlength="2" name="product_qty['.$product_code.']" value="'.$product_qty.'" /></td>';
        echo '<td>'.$product_name.'</td>';
        echo '<td><input type="checkbox" name="remove_code[]" value="'.$product_code.'" /> Remove</td>';
        echo '</tr>';
        $subtotal = ($product_price * $product_qty);
        $total = ($total + $subtotal);}
    }
    echo '<td colspan="4">';
    echo '<button type="submit">Update</button><a href="view_cart.php">Checkout</a>';
    echo '</td>';
    echo '</tbody>';
    echo '</table>';
    
    $current_url = urlencode($url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
    echo '<input type="hidden" name="return_url" value="'.$current_url.'" />';
    echo '</form>';
    echo '</div>';
}
?>
</div>
</html>