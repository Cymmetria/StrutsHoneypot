#include "httpd.h"
#include "http_config.h"
#include "http_core.h"
#include "http_log.h"
#include "http_main.h"
#include "http_protocol.h"
#include "http_request.h"
#include "util_script.h"
#include "http_connection.h"

#include "apr_strings.h"
#include "apr_tables.h"

#include <stdio.h>


#define CONTENT_TYPE_REGEXP "(%{|#)"

#define WARN_ONLY 1

static int x_handler(request_rec *r) {
    char *val = NULL;
    const apr_array_header_t    *fields = NULL;
    int                         i = 0, match = 0;
    apr_table_entry_t           *e = 0;
    ap_regex_t                  *regexp = NULL;
    ap_regmatch_t               regmatch[AP_MAX_REG_MATCH];

    if (r->header_only) {
        return DECLINED;
    }
    // no need to free, this will be freed with the rest of the request pool.
    regexp = ap_pregcomp(r->pool, CONTENT_TYPE_REGEXP, AP_REG_EXTENDED | AP_REG_ICASE);
    if (!regexp) {
	return DECLINED;
    }
    // get header fields
    fields = apr_table_elts(r->headers_in);
    if (fields == NULL) {
        return DECLINED;
    }
    e = (apr_table_entry_t *) fields->elts;
    match = 0;
    for(i = 0; i < fields->nelts; i++) {
        //compare case insensitive for Content-Type headers, if there's a match. continue to execute the regexp.
	if (strcasecmp(e[i].key, "Content-Type") == 0) {
	    int rc = ap_regexec(regexp, e[i].val, AP_MAX_REG_MATCH, regmatch, 0);
	    if (rc == 0) {
                val = e[i].val;
		match = 1;
		break;
            }
	}
    }
    if (!match) {
	return DECLINED;
    }
    else if (match && WARN_ONLY) {
        ap_log_rerror(APLOG_MARK, APLOG_ERR, 0, r, "Content-Type Defense=\"%s\"", val);
	return DECLINED;
    }

    ap_log_rerror(APLOG_MARK, APLOG_ERR, 0, r, "Content-Type Defense=\"%s\"", val);
    ap_set_content_type(r, "text/html");
    ap_rputs(DOCTYPE_HTML_3_2, r);
    ap_rputs("<HTML>\n", r);
    ap_rputs(" <HEAD>\n", r);
    ap_rputs(" </HEAD>\n", r);
    ap_rputs(" <BODY>\n", r);
    ap_rputs("I just love scanning for lifeforms. Lifeforms... you tiny little lifeforms... you precious little lifeforms... where are you?\n<pre>", r);
    ap_rputs("</pre> </BODY>\n", r);
    ap_rputs("</HTML>\n", r);
    return OK;
}

static void register_hooks(apr_pool_t *p) {
	ap_hook_handler(x_handler, NULL, NULL, APR_HOOK_MIDDLE);
}


AP_DECLARE_MODULE(contentrap) = {
   STANDARD20_MODULE_STUFF,
   NULL,        /* create per-dir    config structures */
   NULL,         /* merge  per-dir    config structures */
   NULL,        /* create per-server config structures */
   NULL,         /* merge  per-server config structures */
   NULL,               /* table of config file commands       */
   register_hooks               /* register hooks                      */
};

