GET /_search
{
  "script_fields": {
    "my_field": {
      "script": {
        "inline": "1 + my_var",
        "params": {
          "my_var": 2
        }
      }
    }
  }
}