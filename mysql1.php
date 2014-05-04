$dbcnx = @mysql_connect('localhost', 'BITS', ''); 
if (!$dbcnx) { 
  echo '<p>Unable to connect to the ' . 
      'database server at this time.</p>' ); 
  exit(); 
} 
