# Telematics Ingestion & Reporting API

Symfony 8 / PHP 8.4 API that ingests GNSS + AVL telemetry from Teltonika FMC650 devices and reports distance and fuel per vehicle. MariaDB, Dockerised.

## Quick start

From a clean clone, one command — copies `.env`, builds and starts containers, installs Composer deps, runs migrations:

```bash
make fresh-start
```

App on `:5300`, phpMyAdmin on `:5302`. Day-to-day after that: `make up` (start) / `make down` (stop).

Dev tooling: `make test` (PHPUnit) · `make stan` (PHPStan) · `make rector` (dry-run).

## API

Base URL `http://localhost:5300`. JSON in/out. No auth (MVP).

### `POST /api/vehicles/records` — ingest a batch

`deviceId` identifies the vehicle. Each record carries `gnss` (required position) and `io` (AVL parameters, keyed by AVL ID). Invalid records are skipped, not fatal.

```json
{
  "deviceId": "AVLDID5000",
  "records": [
    {
      "gnss": { "timestamp": 1781849860.548, "latitude": 54.6872, "longitude": 25.2797 },
      "io":   { "4": 112, "24": 40, "239": 1, "240": 1, "21": 4,
                "216": 128340000, "86": 47200000, "231": "ABC", "232": "123" }
    }
  ]
}
```

| AVL ID | Field | Unit |
|---|---|---|
| `1` / `gnss.timestamp` | timestamp | unix epoch seconds |
| `2` `3` / `gnss.latitude` `longitude` | position | WGS84 degrees |
| `4` | altitude | metres |
| `24` | speed | km/h |
| `239` / `240` | ignition / movement | 0 or 1 |
| `21` | GSM signal | 1–5 |
| `216` | total odometer | metres (cumulative) |
| `86` | engine fuel used | millilitres (cumulative) |
| `231` / `232` | plate part 1 / part 2 | string |

**Responses**
- `200` — `{ "batchAccepted": true, "acceptedCount": 1, "rejectedCount": 0 }`
- `422` — envelope invalid (missing `deviceId` / empty `records`): `{ "batchAccepted": false, "deviceId": "..." }`

### `GET /api/vehicles/report` — distance & fuel over a range

| Param | Required | Format |
|---|---|---|
| `plates` | yes | `PART1+PART2` (the `+` separates the two plate parts) |
| `from` | yes | ISO 8601, e.g. `2026-06-18T00:00:00+00:00` |
| `to` | yes | ISO 8601 (must be after `from`) |

```
GET /api/vehicles/report?plates=ABC+123&from=2026-06-18T00:00:00+00:00&to=2026-06-18T23:59:59+00:00
```

**Responses**
- `200` — `{ "vehicle": "ABC+123", "from": "...", "to": "...", "distanceKm": 0.12, "fuelConsumedLitres": 1.5 }`
- `404` — unknown plate or no data in range
- `422` — invalid parameters

Distance and fuel are `last − first` of the cumulative counters in range (assumes monotonic; counter resets not handled). Needs ≥2 readings, else `0`.

## Design decisions

- **Association by `deviceId`, not plate.** The device ID is in every batch; the plate is human-entered and often absent. Records always link to a vehicle even when no plate is sent.
- **Two tables.** `vehicle_record` (append-only readings, billions of rows) + `vehicle_number_plates` (one row per device, the plate lookup). Composite index `(device_id, recorded_at)` serves the report query.
- **Capture as-is.** Ingestion stores what the device sends; it does not correct data. Plates self-heal — each batch upserts, so a later good batch overwrites a bad one.
- **Skip bad records, keep the batch.** One malformed record never drops the whole batch (data can be sparse). Envelope errors reject the request.
- **Report aggregates in the DB** (`MIN`/`MAX`/`COUNT`), returning a handful of numbers instead of loading rows — scales to large ranges.

## Tests & next steps

`make test` runs unit tests covering ingest and report validation. Not yet done (time-boxed): functional tests hitting both endpoints against a test DB; per-record rejection reasons in the response; batched flush + odometer-reset handling.
