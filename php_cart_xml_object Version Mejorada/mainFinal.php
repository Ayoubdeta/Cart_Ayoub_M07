<?php
    include_once("com/cart/clsCart.php");
    include("com/catalog/clsCatalog.php");
    include("com/utils/clsConnections.php");
    include("com/utils/clsUsers.php");
    



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
    
            case 'get_total_cart':
                echo "Total: " . $carrito->getTotal();  
                break;
    
            case 'modify_cart':
                if (isset($_GET['old_item_name']) && isset($_GET['new_quantity'])) {
                    $carrito->modificar($_GET['old_item_name'], $_GET['new_quantity']);  // Modify item quantity
                } else {
                    echo "No se ha proporcionado el nombre del artículo o la nueva cantidad.";
                }
                break;
    
            case 'remove_item_cart':
                if (isset($_GET['item_name'])) {
                    $carrito->eliminarNodoPorId($_GET['item_name']);  
                } else {
                    echo "No se ha proporcionado el nombre del artículo.";
                }
                break;

            case 'add_product_cart':
                if (isset($_GET['product_name']) && isset($_GET['price'])) {
                    $catalogo->Add($_GET['product_name'], $_GET['price']);  
                } else {
                    echo "No se ha proporcionado el nombre del producto o el precio.";
                }
                break;
            case 'modify_product_price_catalog':
                if (isset($_GET['product_name']) && isset($_GET['new_price'])) {
                    $catalogo->modificarPrice($_GET['product_name'], $_GET['new_price']);  
                } else {
                    echo "No se ha proporcionado el nombre del producto o el nuevo precio.";
                }
                break;
            case 'modify_product_stock':
                if (isset($_GET['product_name']) && isset($_GET['new_stock'])) {
                    $catalogo->modificarStock($_GET['product_name'], $_GET['new_stock']);  
                } else {
                    echo "No se ha proporcionado el nombre del producto o la nueva cantidad de stock.";
                }
                break;
            case 'remove_product':
                if (isset($_GET['product_name'])) {
                    $catalogo->removeProduct($_GET['product_name']);  
                } else {
                    echo "No se ha proporcionado el nombre del producto a eliminar.";
                }
                break;
            default:
                echo "¡Acción no válida!";
        }
    } else {
        echo "No se ha especificado ninguna acción.";
    }
    

?>