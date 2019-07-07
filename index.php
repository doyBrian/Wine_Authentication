<!DOCTYPE HTML>  
<html>
<head>
</head>
<body> 

<?php
echo "<h2>Authenticate</h2>";
echo "<h3>Prooftag: Authentication of your Dana Estates collection</h3>";
echo "Beginning with the 2014 vintage, all Dana Estates wines are now sealed with the Prooftag bottle authentication<br>";
echo "Bubble Tag™. This seal allows for verification of authenticity for each bottle of Dana Estates. The numeric code<br>";
echo "found on the seal may be entered below.<br><br>";
?>

<!-- The $_SERVER["PHP_SELF"] below is a super global variable that returns the filename of the currently executing script 
So, the $_SERVER["PHP_SELF"] sends the submitted form data to the page itself, instead of jumping to a different page. 
This way, the user will get error messages on the same page as the form. -->

<!--The htmlspecialchars() function below converts special characters to HTML entities. This means that it will replace HTML 
characters like < and > with &lt; and &gt;. This prevents attackers from exploiting the code by injecting HTML or Javascript 
code (Cross-site Scripting attacks) in forms. -->

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
  <input type="text" name="tag_name" style="text-transform: uppercase">
  <input type="submit" name="submit" value=">>">  
</form><br><br>


<?php
echo "If you have difficulty authenticating your bottle, please contact us directly: inquiries@danaestates.com or<br>";
echo "(707) 963-4365. For more information on Prooftag, <a href='http://www.prooftag.net/' style='text-decoration: none' target='blank'>click here</a>.<br><br><br>";

// define variables and set to values
$tag_name = ""; // initial value will be blank
$match = false;  // flag for when a matching tag name is found, initialized to false

// function to validate user input
function test_input($data) {
    $data = trim($data);              //Strip unnecessary characters (extra space, tab, newline) from the user input data (with the PHP trim() function)
    $data = stripslashes($data);      //Remove backslashes (\) from the user input data (with the PHP stripslashes() function)
    $data = htmlspecialchars($data);  //see explanation above
    return $data;
}

/*
check whether the form has been submitted using $_SERVER["REQUEST_METHOD"]. 
If the REQUEST_METHOD is POST, then the form has been submitted - and it should be validated. 
If it has not been submitted, skip the validation and display a blank form.
*/
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $tag_name = test_input($_POST["tag_name"]);  // call function to validate user input
}

// execute only if tag name has been entered or is not blank
if ($tag_name != "") {
    // load xml file that containes wine list
    $xml=simplexml_load_file("wines.xml") or die("Error: Cannot create object");

    // go through each wine child
    foreach($xml->children() as $wines) {

        // if wine tag matches user input tag name, then set match flag to true and print out information
        if ($wines->tag == $tag_name) {
            $match = true;
            echo "<h3>" . $wines->tag . "</h3>"; 
            echo "<h3>Prooftag Customized Product</h3>";
            echo "<h3>VERIFIED</h3>";
            echo "<h4>You are currently controlling a Bubble Tag.</h4>";
            echo "<h4>The certified product is associated to these customized data:</h4>";
            echo "1. Vendeur: " . $wines->vendor . "<br>"; 
            echo "2. Modele: " . $wines->model . "<br>";
            echo "3. Date de garantie: " . $wines->date . "<br>";
            return; // end loop if match is found
        }
    }
    // if no match is found after the loop, match flag remains false, print out error message
    if ($match === false) {
        echo "The code '" . $tag_name . "' does not exist.</b>";
    }
}

?>

</body>
</html>