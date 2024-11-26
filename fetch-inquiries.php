<?php
$q = isset($_GET['q']) ? $_GET['q'] : '';
$suggestions = ['Schedule an appointment', 'Financial Inquiry', 'Academic Inquiry', 'Professional Inquiry'];

$result = [];
if (!empty($q)) {
    foreach ($suggestions as $suggestion) {
        if (stripos($suggestion, $q) === 0) {
            $result[] = "<div onclick=\"selectSuggestion('$suggestion')\">$suggestion</div>";
        }
    }
}

echo implode('', $result);
?>
