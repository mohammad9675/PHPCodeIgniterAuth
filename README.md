# AuthLib PHP Code Documentation

AuthLib is a CodeIgniter library that provides functions for handling authentication and user management. This library includes methods for checking duplicate values, user login, session management, and more. It is designed to be integrated into a CodeIgniter project to simplify authentication and user-related tasks.

## Getting Started

To use the AuthLib library in your CodeIgniter project, follow these steps:

1. Copy the `AuthLib.php` file into your CodeIgniter's `application/models` directory.

2. Load the library in your controller or model using CodeIgniter's library loading mechanism:

```php
$this->load->model('AuthLib');
```

## Methods

### `findDuplicate($table, $field, $PostedField)`

Checks if a duplicate value exists in the specified table and field.

- `$table`: The name of the database table.
- `$field`: The column to search for duplicates.
- `$PostedField`: The value to check for duplication.

Returns `true` if no duplicate is found, `false` otherwise.

### `findDuplicateData($table, $field, $PostedField)`

Fetches duplicate data from the specified table and field.

- `$table`: The name of the database table.
- `$field`: The column to search for duplicates.
- `$PostedField`: The value to check for duplication.

Returns the found data row if a duplicate is found, `null` otherwise.

### `findDuplicateUsername($username)`

Checks if a username is duplicated across multiple tables.

- `$username`: The username to check for duplication.

Returns `true` if the username is unique, `false` otherwise.

### `add($view, $info, $table, $redirectPage = null, $data = null)`

Adds a new record to the specified table.

- `$view`: The view to load before processing the form.
- `$info`: An array containing data to insert into the table.
- `$table`: The name of the database table.
- `$redirectPage`: Optional. The page to redirect to after the operation.
- `$data`: Optional. Additional data to pass to the view.

### `edit($view, $info, $table, $id_field, $id, $redirectPage = null)`

Edits an existing record in the specified table.

- `$view`: The view to load before processing the form.
- `$info`: An array containing data to update in the table.
- `$table`: The name of the database table.
- `$id_field`: The column representing the ID field.
- `$id`: The ID of the record to edit.
- `$redirectPage`: Optional. The page to redirect to after the operation.

### `hash($password)`

Generates a hash for the provided password.

- `$password`: The password to hash.

Returns the hashed password.

### `updateToken($username, $table)`

Updates the token for a user in the specified table.

- `$username`: The username of the user.
- `$table`: The name of the database table.

Returns the updated token.

### `getToken($table)`

Gets the token for the currently logged-in user.

- `$table`: The name of the database table.

Returns the user's token.

### `checkToken($token, $sessionToken, $redirect)`

Compares two tokens and performs a redirection if they don't match.

- `$token`: The token to compare.
- `$sessionToken`: The token from the session.
- `$redirect`: The page to redirect to if the tokens don't match.

Returns `true` if the tokens match, otherwise performs a redirection.

### `login($username, $password, $table, $SessionName, $redirectSuccess, $redirectFailed)`

Handles user login.

- `$username`: The username provided by the user.
- `$password`: The password provided by the user.
- `$table`: The name of the database table.
- `$SessionName`: The session variable name to set upon successful login.
- `$redirectSuccess`: The page to redirect to after successful login.
- `$redirectFailed`: The page to redirect to after failed login attempt.

### Other Utility Methods

The library also provides methods to retrieve user information, check login status, and generate random numbers.

## Usage

You can utilize the AuthLib methods to streamline user authentication and management tasks within your CodeIgniter project. Refer to the documentation for each method's specific parameters and use cases.

## License

This AuthLib code is provided under the MIT License. Feel free to modify and use it in your projects.

**Note:** This documentation is provided as a brief overview of the AuthLib library. For a more comprehensive understanding of each method, its parameters, and its integration into your project, refer to the actual code and the CodeIgniter documentation.
