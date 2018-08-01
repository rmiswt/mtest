<?php
     /*include("person.class.php");
     include("SoapDiscovery.class.php");
     //第一个参数是类名（生成的wsdl文件就是以它来命名的），即person类，第二个参数是服务的名字（这个可以随便写）。
     $disco = new SoapDiscovery('person','Person');
     $disco->getWSDL();*/
include("person.class.php");
     $objSoapServer = new SoapServer("person.wsdl");//person.wsdl是刚创建的wsdl文件
     $objSoapServer->setClass("person");//注册person类的所有方法
     $objSoapServer->handle();//处理请求

     ?>