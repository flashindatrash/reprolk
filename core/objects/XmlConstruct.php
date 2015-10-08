<?php 
class XmlConstruct extends XMLWriter { 
	
    public function __construct($prm_rootElementName, $prm_xsltFilePath=''){ 
        $this->openMemory(); 
        $this->setIndent(true); 
        $this->setIndentString(' '); 
        $this->startDocument('1.0', 'UTF-8'); 

        if($prm_xsltFilePath){ 
            $this->writePi('xml-stylesheet', 'type="text/xsl" href="'.$prm_xsltFilePath.'"'); 
        } 

        $this->startElement($prm_rootElementName); 
    } 
	
    public function setElement($prm_elementName, $prm_ElementText){ 
        $this->startElement($prm_elementName); 
        $this->text($prm_ElementText); 
        $this->endElement(); 
    } 
	
    public function fromArray($prm_array){ 
      if(is_array($prm_array)){ 
        foreach ($prm_array as $index => $element){ 
          if(is_array($element)){ 
            $this->startElement($index); 
            $this->fromArray($element); 
            $this->endElement(); 
          } 
          else 
            $this->setElement($index, $element);
        } 
      } 
    } 

    public function getDocument(){ 
        $this->endElement(); 
        $this->endDocument(); 
        return $this->outputMemory(); 
    } 

} 