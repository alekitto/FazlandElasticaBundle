Aliased Indexes
===============

You can set up FazlandElasticaBundle to use aliases for indexes which allows you to run an
index population without resetting the index currently being used by the application.

To configure FazlandElasticaBundle to use aliases for an index, set the use_alias option to
true.

```yaml
fazland_elastica:
    indexes:
        app:
            use_alias: simple  # true is an alias for "simple"
```

The process for setting up aliases on an existing application is slightly more complicated
because the bundle is not able to set an alias as the same name as an index. You have some
options on how to handle this:

1) Delete the index from Elasticsearch. This option will make searching unavailable in your
   application until a population has completed itself, and an alias is created.
   
```bash
$ curl -XDELETE 'http://localhost:9200/app/'
```

2) Change the index_name parameter for your index to something new, and manually alias the
   current index to the new index_name, which will then be replaced when you run a repopulate.

```yaml
fazland_elastica:
    indexes:
        app:
            use_alias: true
            index_name: app_prod
```

```bash
$ curl -XPOST 'http://localhost:9200/_aliases' -d '
{
    "actions" : [
        { "add" : { "index" : "app", "alias" : "app_prod" } }
    ]
}'
```

Implementing your own alias strategy
------------------------------------

You can implement your own alias strategy for indexes implementing
`Fazland\ElasticaBundle\AliasStrategyInterface` and defining your class as a service.
Then, in index configuration, set the service id in `use_alias` configuration.

```yaml
fazland_elastica:
    indexes:
        app_myindex:
            use_alias: app.myindex_alias_strategy
```
