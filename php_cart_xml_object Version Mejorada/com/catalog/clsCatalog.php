<?php
    class clsCatalog {

        private $xmlfile="xmldb/catalog.xml";
        private $xml;
        private $loaded = false;
        
        function __construct() { // $catalogo es opcional
            $this->Load();
        }
        
        private function Load(): void {
            if (file_exists($this->xmlfile)) { 
                $this->xml = simplexml_load_file($this->xmlfile); 
            } else {
                $this->xml = new SimpleXMLElement('<products></products>'); 
            }
            $this->loaded=true; 
        }

        function modoEchoShow(): void {
            // Iterate through XML and display each product's values
            foreach ($this->xml->product as $producto) {  // Access each <product> element in the XML
                echo "</br>"."ID: " . (string)$producto['id'] . "<br>"; // Fixed the variable name here
                echo "Nombre: " . $producto->name . "<br>";
                echo "Precio: " . $producto->price . "<br>";
                echo "Stock: " . $producto->stock . "<br><br>";
            }
        }
        
        function Show(): void{
            echo $this->xml->asXML();
            
        }

        // arreglar para catalogo
        function modificarNombre($nombreActual, $nuevoNombreProducto) {
            foreach ($this->xml->product as $producto) {
                if ((string)$producto->name === $nombreActual) {
                    // Cambiar el nombre del producto
                    $producto->name = $nuevoNombreProducto;
                    echo "Nombre del producto modificado";
                }
                // Guardar los cambios en el archivo XML
                $this->save();
            }
        }

        function modificarPrice($nombreProducto, $cantidadNueva): void {
            foreach ($this->xml->product as $producto) {
                if ((string)$producto->name === $nombreProducto) {
                    // Cambiar el nombre del producto
                    $producto->price = $cantidadNueva;
                    echo "Precio modificado";
                }
                // Guardar los cambios en el archivo XML
                $this->save();
            }
        }

        function modificarStock($nombreProducto, $cantidadNueva): void {
            foreach ($this->xml->product as $producto) {
                if ((string)$producto->stock === $nombreProducto) {
                    // Cambiar el nombre del producto
                    $producto->stock = $cantidadNueva;
                    echo "Stock modificado";
                }
                // Guardar los cambios en el archivo XML
                $this->save();
            }
        }

        function subtract($nombreProducto, $cantidadARestar): void {
            foreach ($this->xml->product as $producto) {
                if ((string)$producto->nombre === $nombreProducto) { // Comparar con el nombre del producto
                    $stockActual = (int)$producto->stock; // Convertir a entero para operaciones
                    if ($stockActual >= $cantidadARestar) {
                        $producto->stock = $stockActual - $cantidadARestar; // Restar del stock actual
                    } else {
                        echo "Error: La cantidad a restar es mayor que el stock disponible.";
                    }
                    
                    $this->save();
                    return;
                }
            }
            echo "Error: Producto no encontrado.";
        }
        function ExistsIn($name_product): bool {
            // Recorre cada producto en el XML
            foreach ($this->xml->product as $item) {
                // Compara el nombre del producto con el nombre proporcionado
                if ((string)$item->name === $name_product) {
                    return true; // Producto encontrado
                }
            }
            return false; // Producto no encontrado
        }

        function Add($name_product, $price): void {
            // Verifica si el producto ya existe en el XML
            if ($this->ExistsIn($name_product)) {
                // Si el producto ya existe, actualizar la cantidad de stock
                foreach ($this->xml->product as $item) {
                    if ((string)$item->name === $name_product) {
                        $current_stock = (int)$item->stock;
                        // Asegúrate de que no se sobrepasen los límites de stock
                        $item->stock = $current_stock + 1;
        
                        // Guardar los cambios en el archivo XML
                        $this->save();
                        echo "</br> Producto actualizado: " . $name_product . " Nueva cantidad: " . $item->stock . "</br>";
                        echo "------------------------------</br>";
                        return;
                    }
                }
            } else {
                // Si el producto no existe, añadirlo como nuevo
                echo "</br> Producto añadido: " . $name_product . "</br>";
        
                // Crear un nuevo producto en el XML
                $item = $this->xml->addChild('product');
                $item->addAttribute("id", (string)(count($this->xml->product) + 1));  // Asigna un nuevo ID
                $item->addChild('name', $name_product);
                $item->addChild('price', $price);
                $item->addChild('stock', 1);  // Inicializamos el stock en 1
        
                // Guardar los cambios en el archivo XML
                $this->save();
        
                echo "------------------------------</br>";
            }
        }
        function removeProduct($name_product): void {
            foreach ($this->xml->product as $producto) {
                if ((string)$producto->name === $name_product) {
                    unset($producto[0]);
                    echo "Producto '{$name_product}' eliminado del catálogo.</br>";
                    $this->save();  // Save the changes
                    return;
                }
            }
            echo "Producto '{$name_product}' no encontrado en el catálogo.</br>";
        }
        
        
        




        function save(): void {
            $this->xml->asXML($this->xmlfile);
        }




    }
?>
