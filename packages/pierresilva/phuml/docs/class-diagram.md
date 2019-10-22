# Generate a class diagram

The `phuml:diagram` command will generate a class diagram by scanning the code from a given directory.

It has the following arguments:

* `directory`. The directory with the code to be scanned to generate the class diagram
* `output`. The file name for your `png` class diagram

It has the following options:

* `processor` (`-p`). It will let you choose between `neato` and `dot` to generate the class diagram
* `associations` (`-a`). If present the command will generate association among classes.
  It will extract them from the types of the attributes of the class.
  It will also use the types from the arguments passed to the constructor
* `recursive` (`-r`). If present it will scan the given `directory` recursively
* `hide-private` (`-i`). If present it will exclude private methods and attributes
* `hide-protected` (`-o`). If present it will exclude protected methods and attributes
* `hide-attributes` (`-t`). If present it will exclude all the attributes
* `hide-methods` (`-m`). If present it will exclude all the methods
* `hide-empty-blocks` (`-b`). If present, no empty blocks will be shown
* `theme` (`-e`). Colors and fonts to be used for the diagram.
  There are 3 themes to choose from: `phuml`, which is the default theme, `php`, and `classic`

## Examples

* The following command will produce a class diagram from the `tests/resources/.code/classes` directory.
* The diagram will be saved to the file `example.png` in the current directory.
* It will search for classes and interfaces recursively, because of the `-r` option.
* It will generate associations (`-a`), by inspecting attributes and constructor parameters in all the classes.
* It will use the `dot` command to generate the diagram, because of the `-p dot` option.
* It will only show public methods and attributes because both options `-i` and `-o` are present

```
$ vendor/bin/phuml phuml:diagram -r -a -i -o -p dot tests/resources/.code/classes example.png
```

* The following command will produce a class diagram from the `tests/resources/.code/classes` directory.
* The diagram will be saved to the file `example.png` in the current directory.
* It will search for classes and interfaces recursively, because of the `-r` option.
* It will use the `dot` command to generate the diagram, because of the `-p dot` option.
* It will only show names because both options `-t` and `-m` are present.
* Option `-a` is not present since there's not attributes or constructors to look for associations.

```
$ vendor/bin/phuml phuml:diagram -r -t -m -p dot tests/resources/.code/classes example.png
```

* The following command will produce a class diagram from the `tests/resources/.code/classes` directory.
* The diagram will be saved to the file `example.png` in the current directory.
* It will search for classes and interfaces recursively, because of the `-r` option.
* It will use the `dot` command to generate the diagram, because of the `-p dot` option.
* It will only show names because both options `-t` and `-m` are present.
* Option `-a` is not present since there's not attributes or constructors to look for associations.
* It will not create rows for methods and attributes because of the `-b` option.

```
$ vendor/bin/phuml phuml:diagram -r -t -m -b -p dot tests/resources/.code/classes example.png
```

* The following command will produce a class diagram from the `tests/resources/.code/classes` directory.
* The diagram will be saved to the file `example.png` in the current directory.
* It **WILL NOT** search for classes and interfaces recursively, because of the lack of the `-r` option.
* It **WILL NOT** generate associations because of the lack of the `-a` option.
* It will use the `neato` command to generate the diagram, because of the `-p neato` option.
* It will show all methods and attributes because both options `-i` and `-o` are absent

```
$ vendor/bin/phuml phuml:diagram -p neato tests/resources/.code/classes example.png
```

* The following command will produce a class diagram from the `tests/resources/.code/classes` directory.
* The diagram will be saved to the file `example.png` in the current directory.
* It **WILL NOT** search for classes and interfaces recursively, because of the lack of the `-r` option.
* It **WILL NOT** generate associations because of the lack of the `-a` option.
* It will use the `dot` command to generate the diagram, because of the `-p dot` option.
* It will show all methods and attributes because both options `-i` and `-o` are absent
* It will use colors purple and white because the option `e` is the `php` theme

```
$ vendor/bin/phuml phuml:diagram -p dot -e php tests/resources/.code/classes example.png
```
