Doctrine2 `binary_guid` & `short_guid` type
==============================================================================

Doctrine2's default GUID works as a native data type for platforms that support it (Oracle, MSSQL, PostgreSQL) but falls back to VARCHAR in others (MySQL, SQLite).

This is an extension of the default GUID type to use either BINARY(16) or BIGINT as a fallback type for UUIDs in MySQL instead of VARCHAR.

* BINARY: The `UUID()` function from MySQL is used as the generator function and and hex2bin / bin2hex converters are used.
* SHORT: The `UUID_SHORT()` function from MySQL is used as the generator function and the results are handled as integers.

**N.B.: !!! MySQl's short UUIDs are not standard UUIDs and they are not guaranteed to be unique across (...more than 256...) servers.**

------------------------------------------------------------------------------

Usage
------------------------------------------------------------------------------

Adding the following lines in your `app/config.yml` (for Symfony2 projects) should do the trick:

```
doctrine:
    dbal:
        types:
            binary_guid: MS\Doctrine\DBAL\Types\BinaryGuidType
            short_guid: MS\Doctrine\DBAL\Types\ShortGuidType
        mapping_types:
            binary_guid: binary_guid
            short_guid: short_guid
```

And adding the following Annotation comments in your entity definitions should finish the job:

```
    /**
     * @Id
     * @Column(type="binary_guid")
     * @GeneratedValue(strategy="CUSTOM")
     * @CustomIdGenerator(class="MS\Doctrine\ORM\Id\BinaryGuidGenerator")
     */
```

or

```
    /**
     * @Id
     * @Column(type="short_guid")
     * @GeneratedValue(strategy="CUSTOM")
     * @CustomIdGenerator(class="MS\Doctrine\ORM\Id\ShortGuidGenerator")
     */
```
