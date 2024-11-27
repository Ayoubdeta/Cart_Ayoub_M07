<?php
    include_once("com/cart/clsCart.php");
    include("com/catalog/clsCatalog.php");

    $catalogo = new clsCatalog(); // O el valor que necesitas pasar
    $carrito = new clsCart($catalogo);


    //header('Content-Type: text/xml');
    //$catalogo->Show();
    //$catalogo->modoEchoShow()

    // $carrito->RemoveAllCart();

    //$carrito->Add("Mandarina", 4.25);
    //$carrito->modoEchoShowCart();
    //$carrito->ShowCart();
    
    //$carrito->getTotal();
    //$carrito->modificarCart("Lechugas", "Tomates")
    //$carrito->eliminarNodoPorId("Mandarina");


    if (isset($_GET['action'])) {
        $action = $_GET['action'];
    
        switch ($action) {
            case 'show_catalog':
                header('Content-Type: text/xml');
                $catalogo->Show();
                break;
    
            case 'modo_echo_show_catalog':
                $catalogo->modoEchoShow();
                break;
    
            case 'remove_all_cart':
                $carrito->RemoveAll();
                break;
    
            case 'add_to_cart':
                if (isset($_GET['item_name']) && isset($_GET['price'])) {
                    $carrito->Add($_GET['item_name'], $_GET['price']);
                } else {
                    echo "No se ha proporcionado nombre del artículo o precio.";
                }
                break;
    
            case 'modo_echo_show_cart':
                $carrito->modoEchoShow();
                break;
    
            case 'show_cart':
                header('Content-Type: text/xml');
                $carrito->Show();
                break;
    
            case 'get_total':
                echo "Total: " . $carrito->getTotal();
                break;
    
            case 'modify_cart':
                if (isset($_GET['old_item_name']) && isset($_GET['new_item_name'])) {
                    $carrito->modificar($_GET['old_item_name'], $_GET['new_item_name']);
                } else {
                    echo "No se ha proporcionado el nombre del artículo antiguo o el nuevo nombre del artículo.";
                }
                break;
    
            case 'remove_item':
                if (isset($_GET['item_name'])) {
                    $carrito->eliminarNodoPorId($_GET['item_name']);
                } else {
                    echo "No se ha proporcionado el nombre del artículo.";
                }
                break;
    
            default:
                echo "¡Acción no válida!";
        }
    } else {
        echo "No se ha especificado ninguna acción.";
    }
    
    
    //Mostrar catálogo en formato XML
        //.php?action=show_catalog
    
    //Mostrar catálogo en modo EchoShow
        //.php?action=modo_echo_show_catalog

    //Eliminar todos los elementos del carrito
        //.php?action=remove_all_cart

    //Agregar un artículo al carrito
        //.php?action=add_to_cart&item_name=Mandarina&price=4.25
    
    //Mostrar el carrito en modo EchoShow
        //.php?action=modo_echo_show_cart

    //Mostrar el contenido del carrito
        //.php?action=show_cart
    
    //Obtener el total del carrito
        //.php?action=get_total

    //Modificar un artículo en el carrito
        //.php?action=modify_cart&old_item_name=Lechugas&new_item_name=Tomates

    //Eliminar un artículo específico del carrito
        //.php?action=remove_item&item_name=Mandarina







?>