<?php
class clsCart{

    private int $total;
    private int $num_items;
    private $xml;
    private $xmlfile="xmldb/cart.xml";
    private $catalog;
    private $loaded=false;

    ////////////////////////////////////////
    function __construct() {
        //echo "--constructor";
        $this->Load();
        $this->Analyse();
        $this->catalog = new clsCatalog;
    }
    /////////////////////////////////////
    private function Analyse(){
        $xml=$this->xml;
        $counter= 0;
        foreach($xml->children() as $child){
            $counter = $counter + 1;
        }
        $this->num_items=$counter;

    }
    /////////////////////////////////////
    
    function Load(){
        if (file_exists($this->xmlfile)) { 
            $this->xml = simplexml_load_file($this->xmlfile); 
            } else {
                $this->xml = new SimpleXMLElement('<cart></cart>'); 
            }
            $this->loaded=true;
        }
    /////////////////////////////////////////
    function modoEchoShowCart() {
        // Cargar el archivo XML 
        $this->xml = simplexml_load_file($this->xmlfile);

        // Recorrer el XML y mostrar los valores de cada producto
        foreach ($this->xml->product_item as $product_item) { // Cambiado a "product_item" en vez de "product"
            echo "</br> Nombre: " . (string)$product_item['name_product'] . "<br>";
            echo "Cantidad: " . $product_item->quantity . "<br>";
            echo "Precio: " . $product_item->price_item->price . "<br>";
            echo "Moneda: " . $product_item->price_item->currency . "<br><br>";
        }    
    }
    function ShowCart(){
        echo $this->xml->asXML();
        
    }
    function save(){
        $this->xml->asXML($this->xmlfile);
    }

    ////////////////////////////////////////////
    /*function Add($name_product,$quantity,$price){
        echo "AddToCart </br>";
        echo $name_product;

        // aqui mete la etiqueta "product" dentro de la etiqueta "cart" 
        $item = $this->xml->addChild('product_item');
        $item->addAttribute("name_product",$name_product);
        // aqui añado dentro las diferentes etiquetas en el mismo nivel
        //$item->addChild('name_product', $name_product); 
        $item->addChild('quantity', $quantity); 
        
        // aqui añado dentro de "product" la etiqueta "price_item" en diferente nivel
        $item_price = $item->addChild('price_item');
        // aqui añado dentro de price_item pero entre ellas estan al mismo nivel

        $item_price->addChild('price', $price); 
        $item_price->addChild('currency', "EUR"); 
        

        $this->xml->asXML($this->xmlfile); 

        echo "</br>------------------------------</br>";
    }*/

    // function ExistsInCart()

    function Add($name_product, $price) {
        $product_exists = false;
    
        // Verificar si el producto ya existe en el carrito
        foreach ($this->xml->product_item as $item) {
            if ((string)$item['name_product'] === $name_product) {
                // Si el producto ya existe, actualiza la cantidad
                $current_quantity = (int)$item->quantity;
                $item->quantity = $current_quantity + 1;
    
                // Guardar los cambios en el archivo XML
                $this->xml->asXML($this->xmlfile);
                echo "</br> Producto actualizado: ".$name_product ."Nueva cantidad: ". $item->quantity. "</br>";
                echo "------------------------------</br>";
                $product_exists = true;
                break;
            }
        }
    
        // Si el producto no existe, añadirlo como nuevo
        if (!$product_exists) {
            echo "</br> Producto añadido al carrito: ". $name_product ."</br>";
    
            $item = $this->xml->addChild('product_item');
            $item->addAttribute("name_product", $name_product);
            $item->addChild('quantity', 1);
    
            $item_price = $item->addChild('price_item');
            $item_price->addChild('price', $price);
            $item_price->addChild('currency', "EUR");
    
            // Guardar los cambios en el archivo XML
            $this->xml->asXML($this->xmlfile);
    
            echo "------------------------------</br>";
        }
    }
    ///////////////////////////////////////////////////////////////////
    function RemoveAllCart(){
        $this->xml = simplexml_load_file($this->xmlfile);
        // Buscar y eliminar los nodos con el valor de name_product dado
        foreach ($this->xml->xpath("//product_item") as $node) {
            // Eliminar el nodo
            unset($node[0]); // Eliminar el nodo de SimpleXML
        }

        // Guardar el XML actualizado
        $this->xml->asXML($this->xmlfile);
    }
    

    //////////////////////////////////////
    function getTotal() {
        $this->total = 0.0;
        $this->xml = simplexml_load_file($this->xmlfile);
        foreach ($this->xml->product_item as $item) {
            $quantity = (int)$item->quantity;
            $price = (float)$item->price_item->price;
            $totalproducto = $quantity * $price;
            $this->total += $totalproducto;
        }
        return $this->total;
    }

    function modificarCart($nombreActual, $nuevoNombreProducto) {
        // Cargar el archivo XML
        $this->xml = simplexml_load_file($this->xmlfile);


        foreach ($this->xml->product_item as $producto) {
            if ((string)$producto['name_product'] === $nombreActual) {
                // Cambiar el nombre del producto
                $producto['name_product'] = $nuevoNombreProducto;
                $productoEncontrado = true;
            }
            // Guardar los cambios en el archivo XML
            $this->xml->asXML("xmldb/cart.xml");
        }
    }

    function eliminarNodoPorId($id) {
        // Cargar el archivo XML
        $this->xml = simplexml_load_file($this->xmlfile);;

        // Buscar y eliminar los nodos con el valor de name_product dado
        foreach ($this->xml->xpath("//product_item[@name_product='$id']") as $node) {
            // el @ busca por atributo de la etiqueta
            // Eliminar el nodo
            $nodeP = $node[0]; // Acceder al nodo directamente
            unset($nodeP[0]); // Eliminar el nodo de SimpleXML
        }

        // Guardar el XML actualizado
        $this->xml->asXML($this->xmlfile);
    }

    ////////////////////////////////////
}
?>