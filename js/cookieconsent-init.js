// obtain plugin
var cc = initCookieConsent();

// run plugin with your configuration
cc.run({
    current_lang: 'cs',
    autoclear_cookies: false,                  // default: false
    page_scripts: true,                        // default: false

    // mode: 'opt-in'                          // default: 'opt-in'; value: 'opt-in' or 'opt-out'
    // delay: 0,                               // default: 0
    // auto_language: null                     // default: null; could also be 'browser' or 'document'
    // autorun: true,                          // default: true
    // force_consent: false,                   // default: false
    // hide_from_bots: false,                  // default: false
    // remove_cookie_tables: false             // default: false
    // cookie_name: 'cc_cookie',               // default: 'cc_cookie'
    // cookie_expiration: 182,                 // default: 182 (days)
    // cookie_necessary_only_expiration: 182   // default: disabled
    // cookie_domain: location.hostname,       // default: current domain
    // cookie_path: '/',                       // default: root
    // cookie_same_site: 'Lax',                // default: 'Lax'
    // use_rfc_cookie: false,                  // default: false
    // revision: 0,                            // default: 0

    gui_options: {
        consent_modal: {
            layout: 'cloud',                      // box,cloud,bar
            position: 'bottom center',           // bottom,middle,top + left,right,center
            transition: 'slide'                 // zoom,slide
        },
        settings_modal: {
            layout: 'box',                      // box,bar
            // position: 'left',                // right,left (available only if bar layout selected)
            transition: 'slide'                 // zoom,slide
        }
    },

    onFirstAction: function(){
        console.log('onFirstAction fired');
    },

    onAccept: function (cookie) {
        console.log('onAccept fired ...');
    },

    onChange: function (cookie, changed_preferences) {
        console.log('onChange fired ...');
    },

    languages: {
        'en': {
            consent_modal: {
                title: 'We use cookies!',
                description: 'Hi, this website uses essential cookies to ensure its proper operation and tracking cookies to understand how you interact with it. The latter will be set only after consent. <button type="button" data-cc="c-settings" class="cc-link">Let me choose</button>',
                primary_btn: {
                    text: 'Accept all',
                    role: 'accept_all'              // 'accept_selected' or 'accept_all'
                },
                secondary_btn: {
                    text: 'Reject all',
                    role: 'accept_necessary'        // 'settings' or 'accept_necessary'
                }
            },
            settings_modal: {
                title: 'Cookie preferences',
                save_settings_btn: 'Save settings',
                accept_all_btn: 'Accept all',
                reject_all_btn: 'Reject all',
                close_btn_label: 'Close',
                cookie_table_headers: [
                    {col1: 'Name'},
                    {col2: 'Domain'},
                    {col3: 'Expiration'},
                    {col4: 'Description'}
                ],
                blocks: [
                    {
                        title: 'Cookie usage 📢',
                        description: 'I use cookies to ensure the basic functionalities of the website and to enhance your online experience. You can choose for each category to opt-in/out whenever you want. For more details relative to cookies and other sensitive data, please read the full <a href="#" class="cc-link">privacy policy</a>.'
                    }, {
                        title: 'Strictly necessary cookies',
                        description: 'These cookies are essential for the proper functioning of my website. Without these cookies, the website would not work properly',
                        toggle: {
                            value: 'necessary',
                            enabled: true,
                            readonly: true          // cookie categories with readonly=true are all treated as "necessary cookies"
                        }
                    }, {
                        title: 'Performance and Analytics cookies',
                        description: 'These cookies allow the website to remember the choices you have made in the past',
                        toggle: {
                            value: 'analytics',     // your cookie category
                            enabled: false,
                            readonly: false
                        },
                        cookie_table: [             // list of all expected cookies
                            {
                                col1: '^_ga',       // match all cookies starting with "_ga"
                                col2: 'google.com',
                                col3: '2 years',
                                col4: 'description ...',
                                is_regex: true
                            },
                            {
                                col1: '_gid',
                                col2: 'google.com',
                                col3: '1 day',
                                col4: 'description ...',
                            }
                        ]
                    }, {
                        title: 'Advertisement and Targeting cookies',
                        description: 'These cookies collect information about how you use the website, which pages you visited and which links you clicked on. All of the data is anonymized and cannot be used to identify you',
                        toggle: {
                            value: 'targeting',
                            enabled: false,
                            readonly: false
                        }
                    }, {
                        title: 'More information',
                        description: 'For any queries in relation to our policy on cookies and your choices, please <a class="cc-link" href="#yourcontactpage">contact us</a>.',
                    }
                ]
            }
        }, //end of en language
        //start cs language
        'cs': {
            consent_modal: {
                title: 'Používáme cookies!',
                description: 'Soubory cookie používáme k analýze údajů o našich návštěvnících, ke zlepšení našich webových stránek, zobrazení personalizovaného obsahu a k tomu, abychom vám poskytli skvělý zážitek z webu. <button type="button" data-cc="c-settings" class="cc-link">Podrobnější nastavení</button>',
                primary_btn: {
                    text: 'Přijmout všechny',
                    role: 'accept_all'              // 'accept_selected' or 'accept_all'
                },
                secondary_btn: {
                    text: 'Odmítnout všechny',
                    role: 'accept_necessary'        // 'settings' or 'accept_necessary'
                }
            },
            settings_modal: {
                title: 'Podrobné nastavení',
                save_settings_btn: 'Uložit nastavení',
                accept_all_btn: 'Přijmout všechny',
                reject_all_btn: 'Odmítnout všechny',
                close_btn_label: 'Zavřít',
                cookie_table_headers: [
                    {col1: 'Název'},
                    {col2: 'Doména'},
                    {col3: 'Platnost'},
                    {col4: 'Popis'},
                    {col5: 'Zpracovatel'}
                ],
                blocks: [
                    {
                        title: 'Co jsou cookies 📢',
                        description: 'Soubory cookie používáme k analýze údajů o našich návštěvnících, ke zlepšení našich webových stránek, zobrazení personalizovaného obsahu a k tomu, abychom vám poskytli skvělý zážitek z webu. <a href="/cz/index/cookies" class="cc-link">Více o cookies</a>.'
                    }, {
                        title: 'Funkční cookies',
                        description: 'Tyto soubory cookie jsou nezbytné pro fungování webových stránek, není tedy možné je zakázat. Obvykle se nastavují v reakci na akci, kterou na webu sami provedete, jako je nastavení zabezpečení, přihlášení a vyplňování formulářů. Svůj prohlížeč můžete nastavit tak, aby blokoval soubory cookie nebo o nich zasílal upozornění. Mějte na paměti, že některé stránky nebudou bez těchto souborů fungovat. Tyto soubory cookie neukládají žádné informace, které lze přiřadit konkrétní osobě. Tyto soubory cookie můžeme nastavit my nebo poskytovatelé třetích stran, jejichž služby na webu využíváme. Tyto soubory cookie neukládají žádné informace, které lze přiřadit konkrétní osobě.',
                        toggle: {
                            value: 'necessary',
                            enabled: true,
                            readonly: true          // cookie categories with readonly=true are all treated as "necessary cookies"
                        },
                        cookie_table: [             // list of all expected cookies
                            {
                                col1: 'cc_cookie',       // match all cookies starting with "_ga"
                                col2: 'tipovacka.hcpcefans.cz',
                                col3: '182 dní',
                                col4: 'Ukládá souhlas uživatele s cookies',
                                col5: 'Tipovačka pardubických fanoušků',
                                is_regex: true
                            }
                        ]
                    }, {
                        title: 'Analytické cookies',
                        description: 'Tyto soubory cookie se používají ke zlepšení fungování webových stránek. Umožňují nám rozpoznat a sledovat počet návštěvníků a sledovat, jak návštěvníci web používají. Pomáhají nám zlepšovat způsob, jakým webové stránky fungují, například tím, že uživatelům umožňují snadno najít to, co hledají. Tyto soubory cookie neshromažďují informace, které by vás mohly identifikovat. Pomocí těchto nástrojů analyzujeme a pravidelně zlepšujeme funkčnost našich webových stránek. Získané statistiky můžeme využít ke zlepšení uživatelského komfortu a k tomu, aby byla návštěva webu pro vás jako uživatele zajímavější.',
                        toggle: {
                            value: 'analytics',     // your cookie category
                            enabled: false,
                            readonly: false
                        },
                        cookie_table: [             // list of all expected cookies
                            {
                                col1: '^_ga',       // match all cookies starting with "_ga"
                                col2: 'google.com',
                                col3: '2 roky',
                                col4: 'ID používané k identifikaci uživatelů',
                                col5: 'Tipovačka pardubických fanouškůl',
                                is_regex: true
                            },
                            {
                                col1: '_gid',
                                col2: 'google.com',
                                col3: '1 den',
                                col4: 'Tento soubor cookie nastavuje Google Analytics. Ukládá a aktualizuje jedinečnou hodnotu pro každou navštívenou stránku a slouží k počítání a sledování zobrazení stránek.',
                                col5: 'Tipovačka pardubických fanoušků'
                            }
                        ]
                    }, {
                        title: 'Cookies pro reklamu a cílení',
                        description: 'Tyto soubory cookie shromažďují informace o tom, jak webové stránky používáte, které stránky jste navštívili a na které odkazy jste klikli. Všechny údaje jsou anonymizovány a nelze je použít k vaší identifikaci',
                        toggle: {
                            value: 'targeting',
                            enabled: false,
                            readonly: false
                        }
                    }, {
                        title: 'Více informací',
                        description: 'Máte-li jakékoli dotazy týkající se našich zásad týkajících se souborů cookie a vašich voleb, prosím <a class="cc-link" href="/cz/index/kontakt">kontaktujte nás</a>.',
                    }
                ]
            }
        }
    }
});
