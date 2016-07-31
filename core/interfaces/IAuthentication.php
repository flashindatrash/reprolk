<?php

//интерфейс для контроллеров, которые мы не хотим редиректить на другие страницы (такие как например API)
interface IAuthentication {
	
	public function authenticate($isAvailable, $isLogined);
	
}

?>