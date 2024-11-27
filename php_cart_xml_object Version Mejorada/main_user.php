<?php
    include("com/utils/clsUser.php");
    include("com/cart/clsCart.php");
    include("com/catalog/clsCatalog.php");
    

    $user = new clsUser();
    
    //$user->register("Noel","SalsaPicante");
    $user->login("Ayoub","1234");
    
    $nombre=$user->getUser();

    $cart = new clsCart($nombre);
?>