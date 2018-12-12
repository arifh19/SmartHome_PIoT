// host = '172.16.153.122';	// hostname or IP address
host = '10.33.109.93';	// hostname or IP address
// host = '172.16.153.110';	// hostname or IP address
port = 1885;
//monitoring
topic = '/arifgozi/smartfan/temp';		// topic to subscribe to
topic1 = '/arifgozi/smartfan/lamp';
topic2 = '/arifgozi/smartfan/door';
topic3 = '/arifgozi/smartfan/fan1';

//controlling
topic4 = '/arifgozi/smartfan/fan';	
topic5 = '/arifgozi/smartfan/lamp1';

useTLS = false;
username = null;
password = null;
// username = "jjolie";
// password = "aa";

// path as in "scheme:[//[user:password@]host[:port]][/]path[?query][#fragment]"
//    defaults to "/mqtt"
//    may include query and fragment
//
// path = "/mqtt";
// path = "/data/cloud?device=12345";

cleansession = true;
