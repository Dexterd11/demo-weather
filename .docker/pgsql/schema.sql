CREATE TABLE public.weather_prediction
(
    id         integer                        NOT NULL,
    handler    character varying(255)         NOT NULL,
    city       character varying(255)         NOT NULL,
    datetime   timestamp(0) without time zone NOT NULL,
    scale      character varying(255)         NOT NULL,
    value      int                            NOT NULL,
    updated_at timestamp(0) without time zone NOT NULL
);


ALTER TABLE public.weather_prediction
    OWNER TO demo;

CREATE SEQUENCE public.weather_prediction_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.weather_prediction_id_seq
    OWNER TO demo;
ALTER SEQUENCE public.weather_prediction_id_seq OWNED BY public.weather_prediction.id;
ALTER TABLE ONLY public.weather_prediction
    ALTER COLUMN id SET DEFAULT nextval('public.weather_prediction_id_seq'::regclass);
COPY public.weather_prediction (id, handler, city, datetime, scale, value, updated_at) FROM stdin;
\.


SELECT pg_catalog.setval('public.weather_prediction_id_seq', 1, false);
ALTER TABLE ONLY public.weather_prediction
    ADD CONSTRAINT weather_prediction_pkey PRIMARY KEY (id);
create unique index wp_unique_key on weather_prediction (handler, city, datetime);


