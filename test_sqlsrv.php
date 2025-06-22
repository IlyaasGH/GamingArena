<?php
if (function_exists('sqlsrv_connect')) {
    echo "SQLSRV extension is working!";
} else {
    echo "SQLSRV extension not loaded!";
}
