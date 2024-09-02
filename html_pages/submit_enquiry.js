const express = require('express');
const mysql = require('mysql2');
const bodyParser = require('body-parser');

const app = express();
app.use(bodyParser.urlencoded({ extended: true }));

// Database connection setup
const connection = mysql.createConnection({
  host: 'localhost',
  user: 'root', // Your MySQL username
  password: '', // Your MySQL password
  database: 'schooldb' // Your database name
});

// Route to handle POST requests
app.post('/submit-enquiry', (req, res) => {
  // Collect and sanitize form inputs
  const name = req.body.name.trim();
  const email = req.body.email.trim();
  const message = req.body.message.trim();

  // Validate email
  if (!/\S+@\S+\.\S+/.test(email)) {
    return res.status(400).send("Invalid email format");
  }

  // Prepare the SQL statement
  const sql = 'INSERT INTO enquiries (name, email, message) VALUES (?, ?, ?)';
  
  // Execute the query
  connection.query(sql, [name, email, message], (err, results) => {
    if (err) {
      console.error('Error executing query:', err);
      return res.status(500).send("Error submitting enquiry");
    }
    res.send("New enquiry submitted successfully");
  });
});

// Start the server
app.listen(3000, () => {
  console.log('Server is running on port 3000');
});
