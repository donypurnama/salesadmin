<?php
//$xml = simplexml_load_file("test.xml");
$xmlstr = "<notes>
<note>
<to>Tove</to>
<from>Jani</from>
<heading>Reminder</heading>
<body id='0'>Don't forget me this weekend!</body>
</note>
<note>
<to>Gugi</to>
<from>Doni</from>
<heading>Reminder1</heading>
<body id='1'>Don't forget me this month!</body>
</note>
<note>
<to>Firman</to>
<from>Dani</from>
<heading>Reminder2</heading>
<body id='2'>Don't forget me this year!</body>
</note>
</notes>";
$xml = new SimpleXMLElement($xmlstr); 

echo $xml->getName() . "<br />";

foreach($xml->children() as $child)
  {
  echo $child->getName() . ": " . $child . "<br />";
  }

foreach ($xml->note as $book) {
    echo $book->to." ".$book->body['id'].'<br />';
  }

?> 