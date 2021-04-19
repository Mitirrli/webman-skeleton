# Phinx

## 创建一个迁移脚本
```sh
$ php vendor/bin/phinx create MyNewMigration
```

### Change 方法
Phinx 0.2.0 介绍了一个新功能-逆迁移（回滚）。现在这个功能成为了脚本的默认方法。在这个方法中，你只需要定义 up 的逻辑，Phinx 可以在回滚的时候自动识别出如何down。比如：

```php
<?php

use Phinx\Migration\AbstractMigration;

class CreateUserLoginsTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     *
     * Uncomment this method if you would like to use it.
     */
    public function change()
    {
        // create the table
        $table = $this->table('user_logins');
        $table->addColumn('user_id', 'integer')
              ->addColumn('created', 'datetime')
              ->create();
    }

    /**
     * Migrate Up.
     */
    public function up()
    {

    }

    /**
     * Migrate Down.
     */
    public function down()
    {

    }
}
```

当执行这个迁移脚本，Phinx 将创建 user_logins 表，并且在回滚的时候自动删除该表。注意，当 change 方法存在的时候，up 和 down 方法会被自动忽略。如果你想用这些方法建议你创建另外一个迁移脚本。

> 当在 change() 方法中创建或者更新表的时候你必须使用 create() 或者 update() 方法。当使用 save() 方法时，Phinx无法识别是创建还是修改数据表。

### Phinx只能回滚以下命令：
createTable
renameTable
addColumn
renameColumn
addIndex
addForeignKey

### Up 方法
up方法会在Phinx执行迁移命令时自动执行，并且该脚本之前并没有执行过。你应该将修改数据库的方法写在这个方法里。

### Down 方法
down方法会在Phinx执行回滚命令时自动执行，并且该脚本之前已经执行过迁移。你应该将回滚代码放在这个方法里。

## 字段操作

### 字段类型
biginteger
binary
boolean
date
datetime
decimal
float
integer
string
text
time
timestamp
uuid

### 字段选项

#### 基本类型
|选项|描述|
| --- | --- |
|limit|为string设置最大长度|
|length|limit 的别名|
|default|设置默认值|
|null|允许空|
|after|指定字段放置在哪个字段后面|
|comment|字段注释|

#### decimal类型
|选项|描述|
| --- | --- |
|precision|和 scale 组合设置精度|
|scale|和 precision 组合设置精度|
|signed|开启或关闭 unsigned 选项（仅适用于 MySQL）|

#### integer 和 biginteger 类型
|选项|描述|
| --- | --- |
|identity|开启或关闭自增长|
|signed|开启或关闭 unsigned 选项（仅适用于 MySQL）|

### Limit 选项 和 MySQL
当使用 MySQL adapter，一些其他的字段类型可以通过 integer 、 text 和 blob 创建。使用下面的 Limit 选项
|Limit|字段类型|
| --- | --- |
|BLOG_TINY|TINYBLOB|
|BLOB_REGULAR|BLOG|
|BLOG_MEDIUM|MEDIUMELOG|
|BLOB_LONG|LONGBLOB|
|TEXT_TINY|TINYTEXT|
|TEXT_REGULAR|TEXT|
|TEXT_MEDIUM|MEDIUMTEXT|
|TEXT_LONG|LONGTEXT|
|INT_TINY|TINYINT|
|INT_SMALL|SMALLINT|
|INT_MEDIUM|MEDIUMINT|
|INT_REGULAR|INT|
|INT_BIG|BIGINT|

```php
use Phinx\Db\Adapter\MysqlAdapter;

//...

$table = $this->table('cart_items');
$table->addColumn('user_id', 'integer')
      ->addColumn('product_id', 'integer', array('limit' => MysqlAdapter::INT_BIG))
      ->addColumn('subtype_id', 'integer', array('limit' => MysqlAdapter::INT_SMALL))
      ->addColumn('quantity', 'integer', array('limit' => MysqlAdapter::INT_TINY))
      ->create();
```

### 重命名字段
```
<?php

use Phinx\Migration\AbstractMigration;

class MyNewMigration extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('users');
        $table->renameColumn('bio', 'biography');
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $table = $this->table('users');
        $table->renameColumn('biography', 'bio');
    }
}
```

### 在一个字段后创建字段
```php
<?php

use Phinx\Migration\AbstractMigration;

class MyNewMigration extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('users');
        $table->addColumn('city', 'string', array('after' => 'email'))
              ->update();
    }
}
```

### 删除字段
```php
<?php

use Phinx\Migration\AbstractMigration;

class MyNewMigration extends AbstractMigration
{
    /**
     * Migrate up.
     */
    public function up()
    {
        $table = $this->table('users');
        $table->removeColumn('short_name')
              ->save();
    }
}
```

### 指定字段Limit
```php
<?php

use Phinx\Migration\AbstractMigration;

class MyNewMigration extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('tags');
        $table->addColumn('short_name', 'string', array('limit' => 30))
              ->update();
    }
}
```

### 修改字段属性
```php
<?php

use Phinx\Migration\AbstractMigration;

class MyNewMigration extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $users = $this->table('users');
        $users->changeColumn('email', 'string', array('limit' => 255))
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {

    }
}
```

## 索引操作
### 使用 addIndex() 方法可以指定索引
```php
<?php

use Phinx\Migration\AbstractMigration;

class MyNewMigration extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('users');
        $table->addColumn('city', 'string')
              ->addIndex(array('city'))
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {

    }
}
```

```php
// Phinx 默认创建的是普通索引， 我们可以通过添加 unique 参数来指定唯一值。也可以使用 name 参数来制定索引名。
<?php

use Phinx\Migration\AbstractMigration;

class MyNewMigration extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('users');
        $table->addColumn('email', 'string')
              ->addIndex(array('email'), array('unique' => true, 'name' => 'idx_users_email'))
              ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {

    }
}
```

```php
// MySQL adapter 支持 fulltext 索引。 如果你使用版本低于 5.6 则必须确保数据表是 MyISAM引擎
<?php

use Phinx\Migration\AbstractMigration;

class MyNewMigration extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('users', ['engine' => 'MyISAM']);
        $table->addColumn('email', 'string')
              ->addIndex('email', ['type' => 'fulltext'])
              ->create();
    }
}
```

```php
// 调用 removeIndex() 方法可以删除索引。必须一条条删除
// 当调用 removeIndex() 方法时不需要调用 save() 方法。 索引会立即删除
<?php

use Phinx\Migration\AbstractMigration;

class MyNewMigration extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('users');
        $table->removeIndex(array('email'));

        // alternatively, you can delete an index by its name, ie:
        $table->removeIndexByName('idx_users_email');
    }

    /**
     * Migrate Down.
     */
    public function down()
    {

    }
}
```

## 数据库Seeding
### 创建Seed类
```sh
$ php vendor/bin/phinx seed:create UserSeeder
```

#### Run 方法
Run 方法将在 Phinx 执行 seed:run 时被自动调用。你可以将测试数据的插入写在里面。
> 不像数据库迁移，Phinx 并不记录 seed 是否执行过。这意味着 seeders 可以被重复执行。请在开发的时候记住

### 插入数据
```php
<?php

use Phinx\Seed\AbstractSeed;

class PostsSeeder extends AbstractSeed
{
    public function run()
    {
        $data = array(
            array(
                'body'    => 'foo',
                'created' => date('Y-m-d H:i:s'),
            ),
            array(
                'body'    => 'bar',
                'created' => date('Y-m-d H:i:s'),
            )
        );

        $posts = $this->table('posts');
        $posts->insert($data)
              ->save();
    }
}
```

### 使用Faker库注入(fzaninotto/faker)
```php
<?php

use Phinx\Seed\AbstractSeed;

class UserSeeder extends AbstractSeed
{
    public function run()
    {
        $faker = Faker\Factory::create();
        $data = [];
        for ($i = 0; $i < 100; $i++) {
            $data[] = [
                'username'      => $faker->userName,
                'password'      => sha1($faker->password),
                'password_salt' => sha1('foo'),
                'email'         => $faker->email,
                'first_name'    => $faker->firstName,
                'last_name'     => $faker->lastName,
                'created'       => date('Y-m-d H:i:s'),
            ];
        }

        $this->insert('users', $data);
    }
}
```

### 清空数据表
```php
<?php

use Phinx\Seed\AbstractSeed;

class UserSeeder extends AbstractSeed
{
    public function run()
    {
        $data = [
            [
                'body'    => 'foo',
                'created' => date('Y-m-d H:i:s'),
            ],
            [
                'body'    => 'bar',
                'created' => date('Y-m-d H:i:s'),
            ]
        ];

        $posts = $this->table('posts');
        $posts->insert($data)
              ->save();

        // empty the table
        $posts->truncate();
    }
}
```

### 执行 Seed
默认Phinx会执行所有 seed。 如果你想要指定执行一个，只要增加 -s 参数并接 seed 的名字
```sh
$ php vendor/bin/phinx seed:run -s UserSeeder

$ php vendor/bin/phinx seed:run -s UserSeeder -s PermissionSeeder -s LogSeeder
```

## 命令
### Create 命令
```sh
$ phinx create MyNewMigration
```

### Migrate 命令
```sh
$ phinx migrate -e development -t 20110103081132
```

### Rollback 命令

你可以使用 rollback 命令回滚上一个迁移脚本。不带任何参数
```sh
$ phinx rollback -e development
```

使用 --target 或者 -t 回滚指定版本迁移脚本
```sh
$ phinx rollback -e development -t 20120103083322
```

指定版本如果设置为0则回滚所有脚本
```sh
$ phinx rollback -e development -t 0
```

可以使用 --date 或者 -d 参数回滚指定日期的脚本
```sh
$ phinx rollback -e development -d 2012
$ phinx rollback -e development -d 201201
$ phinx rollback -e development -d 20120103
$ phinx rollback -e development -d 2012010312
$ phinx rollback -e development -d 201201031205
$ phinx rollback -e development -d 20120103120530
```

[参考文章 - phinx-doc](https://tsy12321.gitbooks.io/phinx-doc/content/configuration.html)