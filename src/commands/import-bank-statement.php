<?php declare(strict_types=1);
  require_once(__DIR__ . '/../platform.php');
  require_once(__DIR__ . '/createMovement.php');
  require_once(__DIR__ . '/createStatement.php');
  require_once(__DIR__ . '/../parsers/asnbank-CSV.php');

// E.g.: php src/index.php import-bank-statement asnbank-CSV ./example.csv "2022-03-31 12:00:00"
//                             0                    1           2             3

function importBankStatement($context, $command) {
  $parserFunctions = [
    "asnbank-CSV" => "parseAsnBankCSV",
  ];
  
  if (isset($context["user"])) {
    $format = $command[1];
    $fileName = $command[2];
    $importTime = strtotime($command[3]);
    $type_ = "payment";
    $entries = $parserFunctions[$format](file_get_contents($fileName));
    for ($i = 0; $i < count($entries); $i++) {
        $movementId = intval(createMovement($context, [
        "create-movement",
        $type_,
        strval(getComponentId($entries[$i]["from"])),
        strval(getComponentId($entries[$i]["to"])),
        $entries[$i]["date"],
        $entries[$i]["amount"]
      ])[0]);
        $statementId = intval(createStatement($context, [
        "create-statement",
        $movementId,
        $importTime
      ])[0]);
    }
    return [strval(count($entries))];
  } else {
    return ["User not found or wrong password"];
  }
}