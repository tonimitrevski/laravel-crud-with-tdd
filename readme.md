## Simple Laravel CRUD TDD Application with Repository Pattern Decorator

1. All tests are on the test folder

2. All Repository are in the app/Repositories
<ul>
    <li>They are made with Provider which is included in config/app.php file</li>
    <li>
        On Repository folder has 3 folders
        <ul>
            <li>
                CRUD folder in which are included all default methods
            </li>
            <li>
                Criteria folder for building specific query 
            </li>
            <li>
                PostRepository folder if you want to include specific methods for this table
            </li>
        </ul>
    </li>
</ul>
3. Add Cache Repository Patern Decorator
