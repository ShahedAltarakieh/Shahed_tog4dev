<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayTabs Payment</title>
</head> 
<body>
    <h1>PayTabs Payment</h1>
    
    <form action="{{ route('payment.process') }}" method="POST">
        @csrf
        <div>
            <label for="name">Name:</label>
            <input type="text" name="name" required>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" required>
        </div>
        <div>
            <label for="phone">Phone:</label>
            <input type="text" name="phone" required>
        </div>
        <div>
            <label for="street">Street:</label>
            <input type="text" name="street" required>
        </div>
        <div>
            <label for="city">City:</label>
            <input type="text" name="city" required>
        </div>
        <div>
            <label for="state">State:</label>
            <input type="text" name="state" required>
        </div>
        <div>
            <label for="country">Country:</label>
            <input type="text" name="country" required value="JOR" readonly>
        </div>
        <div>
            <label for="zip">Zip Code:</label>
            <input type="text" name="zip" required>
        </div>
        <div>
            <label for="amount">Amount:</label>
            <input type="number" name="amount" required>
        </div>
        <div>
            <button type="submit">Pay Now</button>
        </div>
    </form>
</body>
</html>
