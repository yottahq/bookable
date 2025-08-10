# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/).

## [Unreleased]

## [v0.1.0-beta.1] – 2025-08-10

### Added
- **SingleDailySlotStrategy** returning `Collection<Slot>` for date-based availability.
- **Slot** value object (`start`, `end`) with helpers: `toArray`, `toJson`, `toCarbonPeriod`, `toLeaguePeriod`, `durationInMinutes`, `overlaps`.
- The `HasBookings` to allow Bookable entities to manage bookings.
- Basic **PHPUnit** test setup and unit tests for `Slot` and `SingleDailySlotStrategy`.
