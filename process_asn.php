<html>
<body>

<?php  

$pdb_asn_info_url = 'https://api.peeringdb.com/api/net?asn='. $_POST['asn'];
$pdb_peering_info_url = 'https://api.peeringdb.com/api/netixlan?asn='. $_POST['asn'];

$response_json = file_get_contents($pdb_asn_info_url);
$response_array = json_decode($response_json, true);

$org_name = $response_array['data'][0]['name'];

echo 'Executive Summary of peering info for ASN ' . $_POST['asn'] . '<br>';
echo '<hr>';
echo 'This ASN belongs to: '. $org_name . '<br>';

$response_json = file_get_contents($pdb_peering_info_url);

$response_array = json_decode($response_json, true);

$total_ixps = count($response_array['data']);

echo '<hr>';
echo $org_name . ' has presents in the following public IXPs:' . '<br>';

$aggregated_bandwidth = 0;
$total_peerings = 0;
$unique_ixp_array = array();
$j = 1;

for ($i = 0; $i < $total_ixps; $i++) {

	if (isset($response_array['data'][$i]['ipaddr4'])) {
		$total_peerings += 1;
	}

	if (isset($response_array['data'][$i]['ipaddr6'])) {
		$total_peerings += 1;
	}


	$current_ixp = $response_array['data'][$i]['name'];

	if ( ! in_array($current_ixp, $unique_ixp_array)) {
		echo '[' . $j . ']' . $current_ixp . ' ';
		$j++;
		array_push($unique_ixp_array, $current_ixp);
	}
	
	$aggregated_bandwidth += $response_array['data'][$i]['speed'] / 1000;
}

echo '<hr>';

echo $org_name . ' has total number of ' . $total_peerings . ' IPv4/IPv6 peerings/peering fabric endpoints' . '<br>';
echo '<hr>';

echo $org_name . ' has total aggregated bandwidith of ' . $aggregated_bandwidth . 'Gbps on all IXP peering fabrics <br>';
echo '<hr>';
?>

</body>
</html>
