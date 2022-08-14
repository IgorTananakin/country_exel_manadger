<?php
class BD extends PDO{
	
    // //получение всех данных по хеш
	// public function all_data_for_hash($country,$date,$date_pref){
        
    //     $array = $this->query('SELECT * FROM manadger WHERE country = "' . $country . '" AND is_downloaded = 0 LIMIT 100')->fetchAll(PDO::FETCH_ASSOC);//подучение данных по переданному хешу
    //     $current_in_bd = $array[0]["timestamp"];//дата в базе
    //     //var_dump($current_in_bd);

        
    //     $current_in_bd = strtotime($current_in_bd);//перевод в unixtime та дата что в базе
    //     $current_in_bd_plus_1_week = strtotime('+7 day', $current_in_bd);//перевод в unixtime та дата что в базе + 1 неделя
    //     $date = strtotime($date);//перевод в unixtime текущую дату
        
    //     if ($current_in_bd_plus_1_week < $date) {
            
    //         return $array;//возвращаем если условие удовлетворяет
    //     } else {
    //         return null;
    //     }
        
	// }

    //получение всех данных по хеш
	public function all_data_for_hash($country,$date,$sunday,$date_pref){
        
        
        $array = $this->query('SELECT * FROM manadger WHERE country = "' . $country . '" AND is_downloaded = 0 
                                AND manadger.timestamp < "' . $sunday . '"
                                AND manadger.date_update_true < "' . $date . '" LIMIT 100')->fetchAll(PDO::FETCH_ASSOC);//подучение данных по переданному хешу
        
        
        // $current_in_bd = $array[0]["timestamp"];//дата в базе
        // //var_dump($current_in_bd);

        
        // $current_in_bd = strtotime($current_in_bd);//перевод в unixtime та дата что в базе
        // $current_in_bd_plus_1_week = strtotime('+7 day', $current_in_bd);//перевод в unixtime та дата что в базе + 1 неделя
        // $date = strtotime($date);//перевод в unixtime текущую дату
        
        if (!empty($array)) {
            
            return $array;//возвращаем если условие удовлетворяет
        } else {
            return null;
        }
        
	}

    // //обновление данных по дате
    // public function udate_date($country,$date,$user_id,$date_pref){
    //     //, is_downloaded = 1
    //     $request = 'UPDATE manadger SET manadger.timestamp = "' . $date . '", manadger.is_downloaded = 1  
    //     WHERE manadger.country = "' . $country . '" AND manadger.is_downloaded = 0 LIMIT 100 ';
        
    //     //echo ' update ' . $request;
    //     //$this->query('UPDATE manadger SET manadger.timestamp = "' . $date . '"  WHERE manadger.country = "' . $country . '" ')->fetchAll(PDO::FETCH_ASSOC);

    //     //, manadger.user_id_is_downloaded = "123"
    //     //обновил дату и что менеджер загрузил у 100 записей на текущую неделю и указал какой менеджер загрузил
	// 	$this->query('UPDATE manadger SET manadger.timestamp = "' . $date . '", manadger.is_downloaded = 1 ,
    //                  manadger.user_id_is_downloaded = "' . $user_id . '"
    //                  WHERE manadger.country = "' . $country . '" AND manadger.is_downloaded = 0 LIMIT 100')->fetchAll(PDO::FETCH_ASSOC);
    //     //обновил дату во всех записях на текущую дату
	// 	$this->query('UPDATE manadger SET manadger.timestamp = "' . $date . '", manadger.is_downloaded = 0  
    //                   WHERE manadger.country = "' . $country . '" AND manadger.is_downloaded = 0 LIMIT 100')->fetchAll(PDO::FETCH_ASSOC);             
    //     return 'true';
    // }

    //обновление данных по дате
    public function udate_date($country,$date,$user_id,$sunday,$date_pref){
        //, is_downloaded = 1
        $request = 'UPDATE manadger SET manadger.timestamp = "' . $date . '", manadger.is_downloaded = 1  
        WHERE manadger.country = "' . $country . '" AND manadger.is_downloaded = 0 LIMIT 100 ';
        
        //echo ' update ' . $request;
        //$this->query('UPDATE manadger SET manadger.timestamp = "' . $date . '"  WHERE manadger.country = "' . $country . '" ')->fetchAll(PDO::FETCH_ASSOC);

        //, manadger.user_id_is_downloaded = "123"
        //обновил дату и что менеджер загрузил у 100 записей на текущую неделю и указал какой менеджер загрузил
		$this->query('UPDATE manadger SET manadger.timestamp = "' . $date . '", manadger.is_downloaded = 1 ,
                     manadger.user_id_is_downloaded = "' . $user_id . '"
                     WHERE manadger.country = "' . $country . '" AND manadger.is_downloaded = 0 LIMIT 100')->fetchAll(PDO::FETCH_ASSOC);
        //обновил дату во всех записях на текущую дату и добавил когда могу выгрузить
		$this->query('UPDATE manadger SET manadger.timestamp = "' . $date . '", manadger.is_downloaded = 0, 
                      manadger.date_update_true = "' . $sunday . '"  
                      WHERE manadger.country = "' . $country . '" AND manadger.is_downloaded = 0')->fetchAll(PDO::FETCH_ASSOC);             
        return 'true';
    }

    // //запрос по хешу
    // public function data_for_menedger($country) {
        
    //     return $this->query('SELECT id_partner,users,email,country,website,date_registration,
    //                                 manadger.status,partner_group,commission_group,attracted_by_partner,
    //                                 commission_group_end_date,players FROM manadger WHERE  manadger.country = "' . $country . '"
    //                                  AND manadger.is_downloaded = 0 LIMIT 100')->fetchAll(PDO::FETCH_ASSOC);
    // }

    //запрос по хешу
    public function data_for_menedger($country,$date) {
        
        return $this->query('SELECT id_partner,users,email,country,website,date_registration,
                                    manadger.status,partner_group,commission_group,attracted_by_partner,
                                    commission_group_end_date,players FROM manadger WHERE  manadger.country = "' . $country . '"
                                     AND manadger.is_downloaded = 0 
                                     AND manadger.date_update_true < "' . $date . '" LIMIT 100')->fetchAll(PDO::FETCH_ASSOC);
    }

    //список хешей по странам
    public function country_hash() {
        
        return $this->query('SELECT DISTINCT country,country_hash FROM manadger')->fetchAll(PDO::FETCH_ASSOC);
    }

    //проверить есть ли в базе доступные записи по стране
    public function country_is($country) {
        
        return $this->query('SELECT country FROM manadger WHERE country = "' . $country . '" AND is_downloaded = 0')->fetchAll(PDO::FETCH_ASSOC);
    }

    //получить страну
    public function country($country_hash) {
        
        return $this->query('SELECT DISTINCT country FROM manadger WHERE country_hash = "' . $country_hash . '"')->fetchAll(PDO::FETCH_ASSOC);
    }
	

	

	
	
	
}
