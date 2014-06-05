<?php

class Template extends View{
	
	public function setItems($items){
		$this->pushAttribute("items",$items);
	}
	
}

?>