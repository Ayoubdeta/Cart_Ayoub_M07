<?php
class clsCart{

    private int $total;
    private int $num_items;
    private $xml;
    private $xmlfile;
    private $catalog;
    private $loaded=false;

    ////////////////////////////////////////
    function __construct($username) {
        //echo "--constructor";
        $this->xmlfile = "xmldb/" . $username ."_cart.xml";
        $this->Load();
        $this->Analyse();
        $this->catalog = new clsCatalog();
        
    }
    /////////////////////////////////////
    private function Analyse(): void{
        $xml=$this->xml;
        $counter= 0;
        foreach($xml->children() as $child){
            $counter = $counter + 1;
        }
        $this->num_items=$counter;

    }

    function getNumItems(): int{
        return $this->num_items;
    }
    /////////////////////////////////////
    
    function Load(): void{
        if (file_exists($this->xmlfile)) { 
            $this->xml = simplexml_load_file($this->xmlfile); 
        } else {
            $this->xml = new SimpleXMLElement('<cart></cart>'); 
        }
        $this->save();
        $this->loaded=true;
    }
    /////////////////////////////////////////
    function modoEchoShow(): void {
        // Cargar el archivo XML 

        // Recorrer el XML y mostrar los valores de cada producto
        foreach ($this->xml->product_item as $product_item) { // Cambiado a "product_item" en vez de "product"
            echo "</br> Nombre: " . (string)$product_item['name_product'] . "<br>";
            echo "Cantidad: " . $product_item->quantity . "<br>";
            echo "Precio: " . $product_item->price_item->price . "<br>";
            echo "Moneda: " . $product_item->price_item->currency . "<br><br>";

            echo "Total del carrito: ".$this->getTotal()."<br>";
            echo "Total de objetos en el carrito: ".$this->getNumItems();
        }    
    }
    function Show(): void{
        echo $this->xml->asXML();
        
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

    /*

    function Add($name_product, $price): void {
        $product_exists = false;
    
        // Verificar si el producto ya existe en el carrito
        foreach ($this->xml->product_item as $item) {
            if ((string)$item['name_product'] === $name_product) {
                // Si el producto ya existe, actualiza la cantidad
                $current_quantity = (int)$item->quantity;
                $item->quantity = $current_quantity + 1;
                $this->catalog->subtract($name_product, 1);
    
                // Guardar los cambios en el archivo XML
                $this->save();
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
    */

    function ExistsIn($name_product): bool {
        foreach ($this->xml->product_item as $item) {
            if ((string)$item['name_product'] === $name_product) {
                return true; // Producto encontrado en el carrito
            }
        }
        return false; // Producto no encontrado
    }
    
    // Función para añadir un producto al carrito
    function Add($name_product, $price): void {
        if ($this->ExistsIn($name_product)) {
            // Si el producto ya existe, actualizar la cantidad
            foreach ($this->xml->product_item as $item) {
                if ((string)$item['name_product'] === $name_product) {
                    $current_quantity = (int)$item->quantity;
                    $item->quantity = $current_quantity + 1;
                    $this->catalog->subtract($name_product, 1);
    
                    // Guardar los cambios en el archivo XML
                    $this->save();
                    echo "</br> Producto actualizado: " . $name_product . " Nueva cantidad: " . $item->quantity . "</br>";
                    echo "------------------------------</br>";
                    return;
                }
            }
        } else {
            // Si el producto no existe, añadirlo como nuevo
            echo "</br> Producto añadido al carrito: " . $name_product . "</br>";
    
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
    function RemoveAll(): void{
        // Buscar y eliminar los nodos con el valor de name_product dado
        foreach ($this->xml->xpath("//product_item") as $node) {
            // Eliminar el nodo
            unset($node[0]); // Eliminar el nodo de SimpleXML
        }

        // Guardar el XML actualizado
        $this->save();
    }
    

    //////////////////////////////////////
    function getTotal():float {
        $this->total = 0.0;
        foreach ($this->xml->product_item as $item) {
            $quantity = (int)$item->quantity;
            $price = (float)$item->price_item->price;
            $totalproducto = $quantity * $price;
            $this->total += $totalproducto;
        }
        return $this->total;
    }

    // Modificar cantidad
    function modificar($nombreActual, $cantidad): void {
        // Verificar si el producto actual existe en el carrito
        if (!$this->ExistsIn($nombreActual)) {
            echo "El producto '{$nombreActual}' no existe en el carrito y no se puede modificar la cantidad.</br>";
            return;
        }
    
        // Si el producto existe, realizar la modificación
        foreach ($this->xml->product_item as $producto) {
            if ((string)$producto['name_product'] === $nombreActual) {
                // Cambiar la cantidad del producto
                $producto->quantity = $cantidad;
                echo "La cantidad del producto '{$nombreActual}' ha sido actualizada a {$cantidad}.</br>";
    
                // Guardar los cambios en el archivo XML
                $this->save();
                break;
            }
        }
    }
    
    /*
    function eliminarNodoPorId($id): void {
        // Buscar y eliminar los nodos con el valor de name_product dado
        foreach ($this->xml->xpath("//product_item[@name_product='$id']") as $node) {
            // el @ busca por atributo de la etiqueta
            // Eliminar el nodo
            $nodeP = $node[0]; // Acceder al nodo directamente
            unset($nodeP[0]); // Eliminar el nodo de SimpleXML
        }

        // Guardar el XML actualizado
        $this->save();
    }
    */

    function eliminarNodoPorId($id): void {
        // Verificar si el producto existe en el carrito
        if (!$this->ExistsIn($id)) {
            echo "El producto  '{$id}' no existe en el carrito y no se puede eliminar.</br>";
            return;
        }
    
        // Buscar y eliminar los nodos con el valor de name_product dado
        foreach ($this->xml->xpath("//product_item[@name_product='$id']") as $node) {
            // Eliminar el nodo
            unset($node[0]); // Eliminar el nodo de SimpleXML
            echo "El producto con ID '{$id}' ha sido eliminado del carrito.</br>";
        }
    
        // Guardar el XML actualizado
        $this->save();
    }

    ////////////////////////////////////


    function save(): void{
        $this->xml->asXML($this->xmlfile);
    }
}
?>