using Genius.Data.Models.Expert;
using Genius.Expert.Interfaces;
using System.Collections.Generic;

namespace Genius.Expert
{
    public class SolverResponse : ISolverResponse
    {
        public int SystemId { get; set; } = 0;

        public bool IsMultiple { get; set; } = false;

        public bool IsSolved { get; set; } = false;

        public SolverStatus Status { get; set; } = SolverStatus.Unknown;

        public IEnumerable<Condition> NextConditions { get; set; } = new List<Condition>();

        public IEnumerable<Product> ResultingProducts { get; set; } = new List<Product>();
    }
}
