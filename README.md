Doctrine2 `short_guid` type
==============================================================================

Doctrine2's default GUID works as a native data type for platforms that support it (Oracle, MSSQL, PostgreSQL) but falls back to VARCHAR in others (MySQL, SQLite).

This is an extension of the default GUID to use BIGINT as a fallback type in MySQL instead of VARCHAR.

The `UUID_SHORT()` function from MySQL is used as the generator function.

------------------------------------------------------------------------------

Usage
------------------------------------------------------------------------------

Adding the following lines in your `app/config.yml` (for Symfony2 projects) should do the trick:

```
doctrine:
    dbal:
        types:    { short_guid: MS\Doctrine\DBAL\Types\ShortGuidType }
        mapping_types: { short_guid: short_guid }
```

And adding the following Annotation comments in your entity definitions should finish the job:

```
    /**
     * @Id
     * @Column(type="short_guid")
     * @GeneratedValue(strategy="CUSTOM")
     * @CustomIdGenerator(class="MS\Doctrine\ORM\Id\ShortGuidGenerator")
     */
```