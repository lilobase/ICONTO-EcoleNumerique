Magpie's rss_fetch.inc file is updated
. line 34 to change defautl snoopy path
. line 240 in function error to disable trigger_error call. (if there's a problem, magpie's fetch function should return null and don't trouble anything else)