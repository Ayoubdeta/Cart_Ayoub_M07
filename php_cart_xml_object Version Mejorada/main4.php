<?php
    include("com/utils/clsUser.php");
    include("com/cart/clsCart.php");
    include("com/catalog/clsCatalog.php");
    

    $user = new clsUser();
    $catalogo = new clsCatalog();
    //$cart = new clsCart();
    
    //$user->register("Noel","SalsaPicante");
    $user->login("Ayoub","1234");
    
    $nombre=$user->getUser();

    $cart = new clsCart($nombre);


    

    $catalogo->modificarNombre("Lechugas","Tomates");
    //$cart->modificar("Mandarina",5);

    $cart->eliminarNodoPorId("Mandarina");
    $catalogo->Add("Sandias",4.25);
    $catalogo->removeProduct("Melones");



?>