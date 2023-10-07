<?php
class Database
{
    private $connection;

    public function __construct()
    {
        $this->connect_db();
    }

    public function connect_db()
    {
        $servername = "172.31.22.43"; 
        $username = "Agnel200555888"; 
        $password = "0ls-0ahZnU"; 
        $dbname = "Agnel200555888"; 

        $this->connection = new mysqli($servername, $username, $password, $dbname);

        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    public function createOrder($pizza_size, $toppings, $delivery_address, $phone_number, $email, $quantity, $crust)
    {
        $pizza_size = $this->sanitize($pizza_size);
        $toppings = $this->sanitize(implode(", ", $toppings));
        $delivery_address = $this->sanitize($delivery_address);
        $phone_number = $this->sanitize($phone_number);
        $email = $this->sanitize($email);
        $quantity = $this->sanitize($quantity);
        $crust = $this->sanitize($crust);

        $sql = "INSERT INTO orders (pizza_size, toppings, delivery_address, phone_number, email, quantity, crust)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("sssssis", $pizza_size, $toppings, $delivery_address, $phone_number, $email, $quantity, $crust);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function sanitize($var)
    {
        return $this->connection->real_escape_string($var);
    }
}

$database = new Database();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pizza_size = $_POST["pizza_size"];
    $toppings = isset($_POST["toppings"]) ? $_POST["toppings"] : [];
    $delivery_address = $_POST["delivery_address"];
    $phone_number = $_POST["phone_number"];
    $email = $_POST["email"];
    $quantity = $_POST["quantity"];
    $crust = $_POST["crust"];

    if ($database->createOrder($pizza_size, $toppings, $delivery_address, $phone_number, $email, $quantity, $crust)) {
        echo "<h2>Thank you for your order!</h2>";
        echo "<p>Your order details have been saved.</p>";
    } else {
        echo "Error: Unable to process your order.";
    }
} else {
    header("Location: order.html");
    exit();
}
?>
