 <?php
    $client = new SoapClient("person.wsdl");
//     $client = new SoapClient("http://192.168.3.2:81/api/server.php?wsdl");//这样也行
    echo($client->say());
    echo "<br />";
    echo($client->run());
    echo "<br />";
    ?>