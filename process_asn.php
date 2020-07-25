<html>
<body>

<?php  

$pdb_asn_info_url = 'https://api.peeringdb.com/api/net?asn='. $_POST['asn'];
$pdb_peering_info_url = 'https://api.peeringdb.com/api/netixlan?asn='. $_POST['asn'];

$response_json = file_get_contents($pdb_asn_info_url);
$response_array = json_decode($response_json, true);

$org_name = $response_array['data'][0]['name'];

echo 'Executive Summary of peering info for ASN ' . $_POST['asn'] . '<br>';
echo 'This ASN belongs to: '. $org_name . '<br>';

$response_json = file_get_contents($pdb_peering_info_url);

$response_array = json_decode($response_json, true);

$total_peerings = count($response_array['data']);

echo $org_name . ' has total number of ' . $total_peerings . ' IPv4/IPv6 peerings/peering fabric endpoints' . '<br>';

echo $org_name . ' has presents in the following public IXPs:' . '<br>';

$aggregated_bandwidth = 0;
for ($i = 0; $i < $total_peerings; $i++) {
	echo $response_array['data'][$i]['name'] . '<br> ';
	$aggregated_bandwidth += $response_array['data'][$i]['speed'] / 1000;
}

echo $org_name . ' has total aggregated bandwidith of ' . $aggregated_bandwidth . 'Gbps on all IXP peering fabrics <br>';
?>

</body>
</html>
