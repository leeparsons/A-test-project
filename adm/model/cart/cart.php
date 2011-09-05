<?php

    class cartModel {

        public function getCostOptions() {
         
            $db = data::instantiate();
            
                
                    
            $arr = $db->query("SELECT ol.opnID,GROUP_CONCAT(ol.cost SEPARATOR '__') AS cost,GROUP_CONCAT(ol.description SEPARATOR '__') AS description,io.name FROM optionCategoryLinks AS ocl LEFT JOIN optionlinks AS ol ON ol.opnID = ocl.opnID LEFT JOIN itemoptions AS io ON io.id = ol.opnID",'ARRAY_A');
            $db->close();

            
            
        }
        
        
    }