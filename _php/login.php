<?php
include("checkout.php");
if(checkout())
{ // DEBUT DE LA VERIFICATION
?>
<form action="login.php" method="post">
<p>
    <input type="text" name="prenom" id="x"/>
    <input type="text" name="prenom" id="y"/>
    <input type="submit" value="Valider" />
</p>
</form>

<style type="text/css">
#x
{
margin: 5px;
width: 500px;
height: 50px;
display: block;
border: 5px solid black;
}

#y
{
margin: 5px;
width: 500px;
height: 50px;
display: block;
border: 5px solid black;
}
</style>
<?php
} // FIN DE LA VERIFICATION
?>