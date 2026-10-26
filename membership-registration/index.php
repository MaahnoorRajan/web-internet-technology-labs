<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Membership Registration</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="form-container">

        <form action="process.php" method="POST">

            <table>

                <tr>
                    <th colspan="2">MEMBERSHIP REGISTRATION SCREEN</th>
                </tr>

                <tr>
                    <td>
                        <label for="first_name">First Name</label>
                    </td>
                    <td>
                        <input type="text" id="first_name" name="first_name">
                    </td>
                </tr>

                <tr>
                    <td>
                        <label for="last_name">Last Name</label>
                    </td>
                    <td>
                        <input type="text" id="last_name" name="last_name">
                    </td>
                </tr>

                <tr>
                    <td>
                        <label for="email">Email</label>
                    </td>
                    <td>
                        <input type="email" id="email" name="email">
                    </td>
                </tr>

                <tr>
                    <td>
                        <label for="password">Password</label>
                    </td>
                    <td>
                        <input type="password" id="password" name="password">
                    </td>
                </tr>

                <tr>
                    <td>
                        <label for="school">School Graduated From</label>
                    </td>
                    <td>
                        <select id="school" name="school">
                            <option value="Primary School">Primary School</option>
                            <option value="Secondary School">Secondary School</option>
                            <option value="High School">High School</option>
                            <option value="College">College</option>
                            <option value="University">University</option>
                            <option value="Other">Other</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>
                        <label for="occupation">Occupation</label>
                    </td>
                    <td>
                        <input type="text" id="occupation" name="occupation">
                    </td>
                </tr>

                <!-- Challenge Task -->
                <tr>
                    <td>
                        <label for="city">City</label>
                    </td>
                    <td>
                        <input type="text" id="city" name="city">
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <button type="submit">Sign Up</button>
                    </td>
                </tr>

            </table>

        </form>

    </div>

</body>
</html>