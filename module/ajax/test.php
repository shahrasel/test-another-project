<?php

//print_r (getConfigValue('perform_data'));
print_r ($page_data );
print_r ($routing_data );
//for db acces use 
print_r (getConfigValue('dbhandler')->db);
//getConfigValue('dbhandler')->db->GetAll(); multiple
//getConfigValue('dbhandler')->db->GetRow(); multiple

?>