// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using Genius.Data.Models.Expert;
using Microsoft.EntityFrameworkCore;

namespace Genius.Data.Contexts;

/// <summary>
/// Represents the database context for expert systems.
/// </summary>
/// Add-Migration InitialCreate -OutputDir Data/Migrations/Expert -Context ExpertContext
/// Update-Database -Context ExpertContext
public class ExpertContext : DbContext, IExpertContext
{
    /// <inheritdoc />
    public DbSet<Models.Expert.System> Systems { get; set; }

    /// <inheritdoc />
    public DbSet<Product> Products { get; set; }

    /// <inheritdoc />
    public DbSet<Condition> Conditions { get; set; }

    /// <inheritdoc />
    public DbSet<Relation> Relations { get; set; }

    public ExpertContext(DbContextOptions<ExpertContext> options) : base(options)
    {
    }
}
