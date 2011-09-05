<?php

    class menu extends controller {

        public function index() {
            
            $this->dashboard = $this->admUrl . 'dashboard/';
            
            $this->clients = $this->admUrl . 'clients/clients/';

            $this->createClients = $this->admUrl . 'clients/clients/add/';

            
            $this->orders = $this->admUrl . 'orders/';

            $this->productTypes = $this->admUrl . 'products/types';

            $this->productOptions = $this->admUrl . 'products/options';

            
            $this->oneOffs = $this->admUrl . 'products/oneoffs/';
            
            $this->logout = $this->admUrl . 'login/login/logout/';
            
        }

        
        
    }